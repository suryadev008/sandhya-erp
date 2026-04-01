<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeSalary;
use App\Models\ExtraPayment;
use App\Models\LatheProduction;
use App\Models\Payroll;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LathePayslipController extends Controller
{
    /** Show payslip calculation page for a lathe employee */
    public function show(Request $request, string $employeeId)
    {
        $employee = Employee::with(['currentSalary'])->findOrFail($employeeId);

        $currentMonth = (int) now()->format('m');
        $currentYear  = (int) now()->format('Y');

        $selectedMonth = (int) $request->get('month', $currentMonth);
        $selectedYear  = (int) $request->get('year',  $currentYear);

        $monthStart = sprintf('%04d-%02d-01', $selectedYear, $selectedMonth);
        $monthEnd   = date('Y-m-t', strtotime($monthStart));

        // All lathe entries for this employee in the selected month
        $entries = LatheProduction::with(['company', 'part', 'operation'])
            ->where('employee_id', $employeeId)
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        // Group entries by date for display
        $entriesByDate = $entries->groupBy(fn($e) => $e->date->format('Y-m-d'));

        $totalLatheAmount = $entries->sum('amount');

        // Load existing payroll (if already generated)
        $payroll = Payroll::where('employee_id', $employeeId)
            ->where('month', $selectedMonth)
            ->where('year',  $selectedYear)
            ->with('extraPayments')
            ->first();

        $extraPayments = $payroll ? $payroll->extraPayments : collect();
        $extraTotal    = $extraPayments->sum('amount');
        $deductions    = $payroll ? (float) $payroll->deductions    : 0;
        $deductionRemarks = $payroll ? $payroll->deduction_remarks  : '';
        $grossAmount   = $totalLatheAmount + $extraTotal;
        $netAmount     = $grossAmount - $deductions;

        // Salary effective for this month
        $salary = EmployeeSalary::where('employee_id', $employeeId)
            ->where('effect_from', '<=', $monthEnd)
            ->orderBy('effect_from', 'desc')
            ->first();

        // Year range for selector
        $joiningDate = $employee->joining_date ?? $employee->created_at;
        $joiningYear = (int) date('Y', strtotime($joiningDate));
        $years  = range($currentYear, $joiningYear);
        $months = [
            1=>'January',2=>'February',3=>'March',4=>'April',
            5=>'May',6=>'June',7=>'July',8=>'August',
            9=>'September',10=>'October',11=>'November',12=>'December',
        ];

        return view('payrolls.lathe_slip', compact(
            'employee', 'entries', 'entriesByDate',
            'totalLatheAmount', 'extraPayments', 'extraTotal',
            'deductions', 'deductionRemarks',
            'grossAmount', 'netAmount',
            'payroll', 'salary',
            'selectedMonth', 'selectedYear',
            'months', 'years', 'monthStart', 'monthEnd'
        ));
    }

    /** Save payroll (create/update draft) */
    public function save(Request $request, string $employeeId)
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year'  => 'required|integer|min:2020|max:2099',
        ]);

        $month = $validated['month'];
        $year  = $validated['year'];

        $monthStart = sprintf('%04d-%02d-01', $year, $month);
        $monthEnd   = date('Y-m-t', strtotime($monthStart));

        $totalLatheAmount = (float) LatheProduction::where('employee_id', $employeeId)
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->sum('amount');

        $existing = Payroll::where('employee_id', $employeeId)
            ->where('month', $month)
            ->where('year',  $year)
            ->first();

        if ($existing && $existing->status !== 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'Payroll is already ' . ucfirst($existing->status) . ' and cannot be modified.',
            ], 422);
        }

        $extraTotal  = $existing ? (float) ExtraPayment::where('payroll_id', $existing->id)->sum('amount') : 0;
        $deductions  = $existing ? (float) $existing->deductions : 0;
        $grossAmount = $totalLatheAmount + $extraTotal;
        $netAmount   = $grossAmount - $deductions;

        $data = [
            'employee_id'         => (int) $employeeId,
            'month'               => $month,
            'year'                => $year,
            'total_working_days'  => 0,
            'present_days'        => 0,
            'sunday_half_days'    => 0,
            'total_lathe_amount'  => $totalLatheAmount,
            'total_cnc_days'      => 0,
            'cnc_rate_per_day'    => 0,
            'total_cnc_amount'    => 0,
            'extra_payment_total' => $extraTotal,
            'gross_amount'        => $grossAmount,
            'deductions'          => $deductions,
            'net_amount'          => $netAmount,
            'status'              => 'draft',
            'generated_by'        => auth()->id(),
            'generated_at'        => now(),
        ];

        if ($existing) {
            $existing->update($data);
            $payroll = $existing->fresh();
        } else {
            $payroll = Payroll::create($data);
        }

        return response()->json(['success' => true, 'message' => 'Payroll saved.', 'payroll_id' => $payroll->id]);
    }

    /** Add extra payment */
    public function addExtra(Request $request, string $payrollId)
    {
        $payroll = Payroll::findOrFail($payrollId);

        if ($payroll->status !== 'draft') {
            return response()->json(['success' => false, 'message' => 'Only draft payrolls can be modified.'], 422);
        }

        $validated = $request->validate([
            'payment_name' => 'required|string|max:255',
            'amount'       => 'required|numeric|min:0.01',
        ]);

        ExtraPayment::create([
            'payroll_id'   => $payroll->id,
            'employee_id'  => $payroll->employee_id,
            'month'        => $payroll->month,
            'year'         => $payroll->year,
            'payment_name' => $validated['payment_name'],
            'amount'       => $validated['amount'],
            'created_by'   => auth()->id(),
        ]);

        $this->recalculate($payroll);

        return response()->json(['success' => true, 'message' => 'Extra payment added.']);
    }

    /** Remove extra payment */
    public function removeExtra(string $payrollId, string $extraId)
    {
        $payroll = Payroll::findOrFail($payrollId);

        if ($payroll->status !== 'draft') {
            return response()->json(['success' => false, 'message' => 'Only draft payrolls can be modified.'], 422);
        }

        ExtraPayment::where('id', $extraId)->where('payroll_id', $payrollId)->delete();
        $this->recalculate($payroll);

        return response()->json(['success' => true, 'message' => 'Removed.']);
    }

    /** Update deduction */
    public function updateDeduction(Request $request, string $payrollId)
    {
        $payroll = Payroll::findOrFail($payrollId);

        if ($payroll->status !== 'draft') {
            return response()->json(['success' => false, 'message' => 'Only draft payrolls can be modified.'], 422);
        }

        $validated = $request->validate([
            'deductions'        => 'required|numeric|min:0',
            'deduction_remarks' => 'nullable|string|max:500',
        ]);

        if ($validated['deductions'] > $payroll->gross_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Deductions (₹' . number_format($validated['deductions'], 2) . ') cannot exceed gross amount (₹' . number_format($payroll->gross_amount, 2) . ').',
            ], 422);
        }

        $payroll->update([
            'deductions'        => $validated['deductions'],
            'deduction_remarks' => $validated['deduction_remarks'],
            'net_amount'        => $payroll->gross_amount - $validated['deductions'],
        ]);

        return response()->json(['success' => true, 'message' => 'Deduction updated.']);
    }

    /** Update payroll status */
    public function updateStatus(Request $request, string $payrollId)
    {
        $payroll = Payroll::findOrFail($payrollId);

        $validated = $request->validate(['status' => 'required|in:approved,paid']);

        if ($validated['status'] === 'approved' && $payroll->status !== 'draft') {
            return response()->json(['success' => false, 'message' => 'Only draft payrolls can be approved.'], 422);
        }
        if ($validated['status'] === 'paid' && $payroll->status !== 'approved') {
            return response()->json(['success' => false, 'message' => 'Only approved payrolls can be marked paid.'], 422);
        }

        $update = ['status' => $validated['status']];
        if ($validated['status'] === 'approved') {
            $update['approved_by'] = auth()->id();
            $update['approved_at'] = now();
        } elseif ($validated['status'] === 'paid') {
            $update['paid_at'] = now();
        }

        $payroll->update($update);

        return response()->json(['success' => true, 'message' => 'Status updated to ' . ucfirst($validated['status']) . '.']);
    }

    /** Generate and download PDF payslip */
    public function pdf(string $employeeId, string $payrollId)
    {
        $employee = Employee::with(['currentSalary'])->findOrFail($employeeId);
        $payroll  = Payroll::with(['extraPayments'])->findOrFail($payrollId);

        $monthStart = sprintf('%04d-%02d-01', $payroll->year, $payroll->month);
        $monthEnd   = date('Y-m-t', strtotime($monthStart));

        $entries = LatheProduction::with(['company', 'part', 'operation'])
            ->where('employee_id', $employeeId)
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->orderBy('date')
            ->get();

        $entriesByDate = $entries->groupBy(fn($e) => $e->date->format('Y-m-d'));

        $monthName = date('F', mktime(0, 0, 0, $payroll->month, 1, $payroll->year));

        $salary = EmployeeSalary::where('employee_id', $employeeId)
            ->where('effect_from', '<=', $monthEnd)
            ->orderBy('effect_from', 'desc')
            ->first();

        $pdf = Pdf::loadView('payrolls.payslip_pdf', compact(
            'employee', 'payroll', 'entries', 'entriesByDate', 'monthName', 'salary'
        ))->setPaper('a4', 'portrait');

        $filename = 'Payslip_' . $employee->emp_code . '_' . $monthName . '_' . $payroll->year . '.pdf';

        return $pdf->download($filename);
    }

    private function recalculate(Payroll $payroll): void
    {
        $extraTotal  = (float) ExtraPayment::where('payroll_id', $payroll->id)->sum('amount');
        $grossAmount = (float) $payroll->total_lathe_amount + $extraTotal;
        $netAmount   = $grossAmount - (float) $payroll->deductions;

        $payroll->update([
            'extra_payment_total' => $extraTotal,
            'gross_amount'        => $grossAmount,
            'net_amount'          => $netAmount,
        ]);
    }
}
