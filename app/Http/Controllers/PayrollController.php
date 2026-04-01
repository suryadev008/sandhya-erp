<?php

namespace App\Http\Controllers;

use App\DataTables\PayrollDataTable;
use App\Models\Employee;
use App\Models\EmployeeSalary;
use App\Models\ExtraPayment;
use App\Models\LatheProduction;
use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    /** Employee list DataTable */
    public function index(PayrollDataTable $dataTable)
    {
        if (request()->ajax()) {
            return $dataTable->ajax();
        }
        return view('payrolls.index');
    }

    /** Employee payroll history — month-by-month from joining */
    public function show(string $id)
    {
        $employee = Employee::with(['currentSalary', 'salaries'])->findOrFail($id);

        $joiningDate  = $employee->joining_date ?? $employee->created_at->toDateString();
        $joiningMonth = (int) date('m', strtotime($joiningDate));
        $joiningYear  = (int) date('Y', strtotime($joiningDate));

        $currentMonth = (int) now()->format('m');
        $currentYear  = (int) now()->format('Y');

        // Selected year (default: current year)
        $selectedYear = (int) request('year', $currentYear);

        // Clamp selected year
        $selectedYear = max($joiningYear, min($currentYear, $selectedYear));

        // Month range for selected year
        $startMonth = ($selectedYear === $joiningYear) ? $joiningMonth : 1;
        $endMonth   = ($selectedYear === $currentYear) ? $currentMonth : 12;

        // Load payrolls for this employee + year, keyed by month
        $payrolls = Payroll::where('employee_id', $id)
            ->where('year', $selectedYear)
            ->with(['extraPayments', 'generatedBy', 'approvedBy'])
            ->get()
            ->keyBy('month');

        // Build month rows
        $months = [];
        for ($m = $endMonth; $m >= $startMonth; $m--) {
            $months[] = [
                'month'   => $m,
                'year'    => $selectedYear,
                'label'   => date('F', mktime(0, 0, 0, $m, 1, $selectedYear)),
                'payroll' => $payrolls->get($m),
            ];
        }

        // Year selector list
        $years = [];
        for ($y = $currentYear; $y >= $joiningYear; $y--) {
            $years[] = $y;
        }

        // Annual summary
        $annualSummary = [
            'total_gross' => Payroll::where('employee_id', $id)->where('year', $selectedYear)->sum('gross_amount'),
            'total_net'   => Payroll::where('employee_id', $id)->where('year', $selectedYear)->sum('net_amount'),
            'total_deductions' => Payroll::where('employee_id', $id)->where('year', $selectedYear)->sum('deductions'),
            'paid_count'  => Payroll::where('employee_id', $id)->where('year', $selectedYear)->where('status', 'paid')->count(),
            'draft_count' => Payroll::where('employee_id', $id)->where('year', $selectedYear)->where('status', 'draft')->count(),
            'approved_count' => Payroll::where('employee_id', $id)->where('year', $selectedYear)->where('status', 'approved')->count(),
        ];

        return view('payrolls.show', compact('employee', 'months', 'years', 'selectedYear', 'annualSummary'));
    }

    /** Generate (or regenerate) payroll for a given month */
    public function generate(Request $request, string $id)
    {
        $validated = $request->validate([
            'month'              => 'required|integer|min:1|max:12',
            'year'               => 'required|integer|min:2020|max:' . now()->year,
            'total_working_days' => 'required|integer|min:1|max:31',
            'present_days'       => 'required|numeric|min:0|max:31',
            'sunday_half_days'   => 'nullable|numeric|min:0|max:10',
        ]);

        // Prevent generating payroll for a future month
        $payrollDate = \Carbon\Carbon::createFromDate($validated['year'], $validated['month'], 1);
        if ($payrollDate->startOfMonth()->isAfter(now()->startOfMonth())) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot generate payroll for a future month.',
            ], 422);
        }

        $employee = Employee::with('currentSalary')->findOrFail($id);

        // Check if already exists and not draft
        $existing = Payroll::where('employee_id', $id)
            ->where('month', $validated['month'])
            ->where('year', $validated['year'])
            ->first();

        if ($existing && $existing->status !== 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'Payroll for this month is already ' . ucfirst($existing->status) . ' and cannot be regenerated.',
            ], 422);
        }

        // Salary effective at end of month
        $monthEnd = date('Y-m-t', mktime(0, 0, 0, $validated['month'], 1, $validated['year']));
        $salary   = EmployeeSalary::where('employee_id', $id)
            ->where('effect_from', '<=', $monthEnd)
            ->orderBy('effect_from', 'desc')
            ->first();

        $perDay   = $salary ? (float) $salary->per_day : 0;

        // Date range for the month
        $monthStart = $validated['year'] . '-' . str_pad($validated['month'], 2, '0', STR_PAD_LEFT) . '-01';

        // Lathe amount from productions
        $totalLatheAmount = 0;
        if (in_array($employee->employee_type, ['lathe', 'both'])) {
            $totalLatheAmount = (float) LatheProduction::where('employee_id', $id)
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->sum('amount');
        }

        // CNC: present days × per day rate
        $totalCncDays   = 0;
        $cncRatePerDay  = 0;
        $totalCncAmount = 0;
        if (in_array($employee->employee_type, ['cnc', 'both'])) {
            $cncRatePerDay  = $perDay;
            $totalCncDays   = (float) $validated['present_days'];
            $totalCncAmount = $cncRatePerDay * $totalCncDays;
        }

        // Extra payments (already saved separately)
        $extraPaymentTotal = $existing
            ? (float) ExtraPayment::where('payroll_id', $existing->id)->sum('amount')
            : 0;

        $grossAmount = $totalLatheAmount + $totalCncAmount + $extraPaymentTotal;
        $deductions  = $existing ? (float) $existing->deductions : 0;
        $netAmount   = $grossAmount - $deductions;

        $data = [
            'employee_id'         => (int) $id,
            'month'               => $validated['month'],
            'year'                => $validated['year'],
            'total_working_days'  => $validated['total_working_days'],
            'present_days'        => $validated['present_days'],
            'sunday_half_days'    => $validated['sunday_half_days'] ?? 0,
            'total_lathe_amount'  => $totalLatheAmount,
            'total_cnc_days'      => $totalCncDays,
            'cnc_rate_per_day'    => $cncRatePerDay,
            'total_cnc_amount'    => $totalCncAmount,
            'extra_payment_total' => $extraPaymentTotal,
            'gross_amount'        => $grossAmount,
            'deductions'          => $deductions,
            'net_amount'          => $netAmount,
            'status'              => 'draft',
            'generated_by'        => auth()->id(),
            'generated_at'        => now(),
        ];

        if ($existing) {
            $existing->update($data);
        } else {
            Payroll::create($data);
        }

        return response()->json(['success' => true, 'message' => 'Payroll generated successfully.']);
    }

    /** Add extra payment to a payroll */
    public function addExtraPayment(Request $request, string $payrollId)
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

    /** Remove an extra payment */
    public function removeExtraPayment(string $payrollId, string $extraId)
    {
        $payroll = Payroll::findOrFail($payrollId);

        if ($payroll->status !== 'draft') {
            return response()->json(['success' => false, 'message' => 'Only draft payrolls can be modified.'], 422);
        }

        ExtraPayment::where('id', $extraId)->where('payroll_id', $payrollId)->delete();
        $this->recalculate($payroll);

        return response()->json(['success' => true, 'message' => 'Extra payment removed.']);
    }

    /** Update deduction amount */
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

        return response()->json(['success' => true, 'message' => 'Deduction updated successfully.']);
    }

    /** Update payroll workflow status */
    public function updateStatus(Request $request, string $payrollId)
    {
        $payroll = Payroll::findOrFail($payrollId);

        $validated = $request->validate([
            'status' => 'required|in:approved,paid',
        ]);

        // Guard transitions
        if ($validated['status'] === 'approved' && $payroll->status !== 'draft') {
            return response()->json(['success' => false, 'message' => 'Only draft payrolls can be approved.'], 422);
        }
        if ($validated['status'] === 'paid' && $payroll->status !== 'approved') {
            return response()->json(['success' => false, 'message' => 'Only approved payrolls can be marked as paid.'], 422);
        }

        $update = ['status' => $validated['status']];

        if ($validated['status'] === 'approved') {
            $update['approved_by'] = auth()->id();
            $update['approved_at'] = now();
        } elseif ($validated['status'] === 'paid') {
            $update['paid_at'] = now();
        }

        $payroll->update($update);

        return response()->json(['success' => true, 'message' => 'Payroll marked as ' . ucfirst($validated['status']) . '.']);
    }

    /** Get payroll detail for a modal (AJAX) */
    public function detail(string $payrollId)
    {
        $payroll = Payroll::with(['extraPayments', 'generatedBy', 'approvedBy'])->findOrFail($payrollId);
        return response()->json(['success' => true, 'data' => $payroll]);
    }

    /** Recalculate gross/net after extra payment change */
    private function recalculate(Payroll $payroll): void
    {
        $extraTotal  = (float) ExtraPayment::where('payroll_id', $payroll->id)->sum('amount');
        $grossAmount = $payroll->total_lathe_amount + $payroll->total_cnc_amount + $extraTotal;
        $netAmount   = $grossAmount - $payroll->deductions;

        $payroll->update([
            'extra_payment_total' => $extraTotal,
            'gross_amount'        => $grossAmount,
            'net_amount'          => $netAmount,
        ]);
    }
}
