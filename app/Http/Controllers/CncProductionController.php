<?php

namespace App\Http\Controllers;

use App\Models\CncProduction;
use App\Models\Company;
use App\Models\Employee;
use App\Models\EmployeeOperationRate;
use App\Models\Machine;
use App\Models\MachineType;
use App\Models\OperationPrice;
use App\Models\Part;
use App\Models\Payroll;
use Illuminate\Http\Request;

class CncProductionController extends Controller
{
    /** CNC employee list */
    public function index()
    {
        $employees = Employee::whereIn('employee_type', ['cnc', 'both'])
            ->orderBy('emp_code')
            ->get(['id', 'emp_code', 'name', 'employee_type', 'cnc_payment_type', 'status']);

        return view('cnc_productions.index', compact('employees'));
    }

    /** Show all entries for one CNC employee with filters */
    public function show(Request $request, $employeeId)
    {
        $employee = Employee::findOrFail($employeeId);

        $year  = max(2020, min((int) now()->year,  (int) ($request->input('year',  now()->year))));
        $month = max(1,    min(12,                  (int) ($request->input('month', now()->month))));
        $date  = $request->filled('date') ? $request->input('date') : null;

        if ($date && !\Illuminate\Support\Carbon::canBeCreatedFromFormat($date, 'Y-m-d')) {
            $date = null;
        }

        $query = CncProduction::with(['company', 'part', 'machine'])
            ->where('employee_id', $employeeId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month);

        if ($date) {
            $query->whereDate('date', $date);
        }

        $entries = $query->orderBy('date')->orderBy('id')->get();

        // Payroll lock check
        $payroll = Payroll::where('employee_id', $employeeId)
            ->where('month', $month)
            ->where('year', $year)
            ->first();

        $locked = $payroll && in_array($payroll->status, ['approved', 'paid']);

        // Summary for the filtered period
        $totalQty       = $entries->sum('production_qty');
        $totalIncentive = $entries->sum('incentive_qty');
        $totalAmount    = $entries->sum('amount');
        $presentDays    = $entries->pluck('date')->map(fn($d) => $d->toDateString())->unique()->count();

        $cncTypeId = MachineType::whereRaw('LOWER(type_name) = ?', ['cnc'])->value('id');
        $machines  = Machine::active()
            ->when($cncTypeId, fn($q) => $q->where('machine_type_id', $cncTypeId))
            ->orderBy('machine_name')
            ->get(['id', 'machine_name', 'machine_number']);

        $companies = Company::active()->orderBy('company_name')->get(['id', 'company_name']);
        $years     = range(now()->year, 2020);

        return view('cnc_productions.show', compact(
            'employee', 'entries', 'year', 'month', 'date',
            'payroll', 'locked', 'companies', 'machines', 'years',
            'totalQty', 'totalIncentive', 'totalAmount', 'presentDays'
        ));
    }

    /** Create form */
    public function create()
    {
        $employees = Employee::whereIn('employee_type', ['cnc', 'both'])
            ->where('status', 'active')
            ->orderBy('emp_code')
            ->get(['id', 'emp_code', 'name', 'cnc_payment_type', 'cnc_target_per_shift', 'cnc_incentive_rate']);

        $companies = Company::active()->orderBy('company_name')->get(['id', 'company_name']);

        $cncTypeId = MachineType::whereRaw('LOWER(type_name) = ?', ['cnc'])->value('id');
        $machines  = Machine::active()
            ->when($cncTypeId, fn($q) => $q->where('machine_type_id', $cncTypeId))
            ->orderBy('machine_name')
            ->get(['id', 'machine_name', 'machine_number']);

        return view('cnc_productions.create', compact('employees', 'companies', 'machines'));
    }

    /** Store production entries (bulk) */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id'             => 'required|exists:employees,id',
            'date'                    => 'required|date|before_or_equal:today',
            'shift'                   => 'required|in:day,night,A,B,general',
            'machine_id'              => 'nullable|exists:machines,id',
            'rows'                    => 'required|array|min:1',
            'rows.*.company_id'       => 'required|exists:companies,id',
            'rows.*.part_id'          => 'required|exists:parts,id',
            'rows.*.operation_type'   => 'required|in:full_finish,finish_first_side,finish_second_side',
            'rows.*.production_qty'   => 'required|integer|min:0',
            'rows.*.target_qty'       => 'nullable|integer|min:0',
            'rows.*.downtime_type'    => 'nullable|in:power_cut,machine_breakdown,other',
            'rows.*.downtime_minutes' => 'nullable|integer|min:0',
            'rows.*.remark'           => 'nullable|string|max:500',
        ]);

        $employee   = Employee::findOrFail($request->employee_id);
        $date       = $request->input('date');
        $shift      = $request->input('shift');
        $machineId  = $request->input('machine_id') ?: null;
        $isSunday   = \Illuminate\Support\Carbon::parse($date)->isSunday();
        $createdBy  = auth()->id();
        $now        = now();

        $inserts = [];
        foreach ($request->rows as $row) {
            $productionQty = (int) ($row['production_qty'] ?? 0);
            $targetQty     = (int) ($row['target_qty'] ?? $employee->cnc_target_per_shift ?? 90);
            $incentiveQty  = max(0, $productionQty - $targetQty);
            $isHalfDay     = (bool) ($row['is_half_day'] ?? false);

            // Rates depend on payment model
            $ratePerPiece   = 0.0;
            $incentiveRate  = 0.0;
            $amount         = 0.0;

            if ($employee->cnc_payment_type === 'per_piece') {
                // Use employee-specific rate → fallback to global operation price
                $ratePerPiece = EmployeeOperationRate::rateFor(
                    $employee->id,
                    (int) ($row['operation_price_operation_id'] ?? 0),
                    $date
                );
                if ($ratePerPiece === 0.0 && !empty($row['operation_price_operation_id'])) {
                    $ratePerPiece = (float) (OperationPrice::where('operation_id', $row['operation_price_operation_id'])
                        ->where('applicable_from', '<=', $date)
                        ->orderBy('applicable_from', 'desc')
                        ->value('price') ?? 0);
                }
                $amount = $productionQty * $ratePerPiece;
            } else {
                // day_rate: only incentive amount stored; base salary calculated at payroll time
                $incentiveRate = (float) $employee->cnc_incentive_rate;
                $amount        = $incentiveQty * $incentiveRate;
            }

            $targetMet = $productionQty >= $targetQty;

            $inserts[] = [
                'employee_id'      => $employee->id,
                'machine_id'       => $machineId,
                'date'             => $date,
                'shift'            => $shift,
                'company_id'       => $row['company_id'],
                'job_name'         => $row['job_name'] ?? null,
                'part_id'          => $row['part_id'],
                'operation_type'   => $row['operation_type'],
                'production_qty'   => $productionQty,
                'target_qty'       => $targetQty,
                'incentive_qty'    => $incentiveQty,
                'rate_per_piece'   => $ratePerPiece,
                'incentive_rate'   => $incentiveRate,
                'amount'           => $amount,
                'target_met'       => $targetMet,
                'downtime_type'    => $row['downtime_type'] ?? null,
                'downtime_minutes' => $row['downtime_minutes'] ?? 0,
                'is_sunday'        => $isSunday,
                'is_half_day'      => $isHalfDay,
                'remark'           => $row['remark'] ?? null,
                'created_by'       => $createdBy,
                'created_at'       => $now,
                'updated_at'       => $now,
            ];
        }

        CncProduction::insert($inserts);

        return redirect()->route('cnc-productions.create')
            ->with('success', count($inserts) . ' CNC production record(s) saved successfully.');
    }

    /** Update a single entry */
    public function update(Request $request, $id)
    {
        $entry = CncProduction::findOrFail($id);

        $payroll = Payroll::where('employee_id', $entry->employee_id)
            ->where('month', $entry->date->month)
            ->where('year', $entry->date->year)
            ->first();

        if ($payroll && in_array($payroll->status, ['approved', 'paid'])) {
            return response()->json(['error' => 'Payroll is locked for this month.'], 403);
        }

        $request->validate([
            'date'             => 'required|date|before_or_equal:today',
            'shift'            => 'required|in:day,night,A,B,general',
            'company_id'       => 'required|exists:companies,id',
            'part_id'          => 'required|exists:parts,id',
            'operation_type'   => 'required|in:full_finish,finish_first_side,finish_second_side',
            'production_qty'   => 'required|integer|min:0',
            'target_qty'       => 'nullable|integer|min:0',
            'machine_id'       => 'nullable|exists:machines,id',
            'downtime_type'    => 'nullable|in:power_cut,machine_breakdown,other',
            'downtime_minutes' => 'nullable|integer|min:0',
            'is_half_day'      => 'nullable|boolean',
            'remark'           => 'nullable|string|max:500',
        ]);

        $employee      = Employee::findOrFail($entry->employee_id);
        $productionQty = (int) $request->production_qty;
        $targetQty     = (int) ($request->input('target_qty') ?? $employee->cnc_target_per_shift ?? 90);
        $incentiveQty  = max(0, $productionQty - $targetQty);
        $isSunday      = \Illuminate\Support\Carbon::parse($request->date)->isSunday();

        $ratePerPiece  = (float) $entry->rate_per_piece;
        $incentiveRate = (float) $entry->incentive_rate;

        if ($employee->cnc_payment_type === 'per_piece') {
            $amount = $productionQty * $ratePerPiece;
        } else {
            $amount = $incentiveQty * $incentiveRate;
        }

        $entry->update([
            'date'             => $request->date,
            'shift'            => $request->shift,
            'company_id'       => $request->company_id,
            'part_id'          => $request->part_id,
            'operation_type'   => $request->operation_type,
            'machine_id'       => $request->machine_id,
            'production_qty'   => $productionQty,
            'target_qty'       => $targetQty,
            'incentive_qty'    => $incentiveQty,
            'amount'           => $amount,
            'target_met'       => $productionQty >= $targetQty,
            'downtime_type'    => $request->downtime_type,
            'downtime_minutes' => $request->downtime_minutes ?? 0,
            'is_sunday'        => $isSunday,
            'is_half_day'      => (bool) $request->input('is_half_day', false),
            'remark'           => $request->remark,
        ]);

        return response()->json([
            'success' => true,
            'entry'   => $entry->fresh(['company', 'part', 'machine']),
        ]);
    }

    /** Delete a single entry */
    public function destroy($id)
    {
        $entry = CncProduction::findOrFail($id);

        $payroll = Payroll::where('employee_id', $entry->employee_id)
            ->where('month', $entry->date->month)
            ->where('year', $entry->date->year)
            ->first();

        if ($payroll && in_array($payroll->status, ['approved', 'paid'])) {
            return response()->json(['error' => 'Payroll is locked for this month.'], 403);
        }

        $entry->delete();

        return response()->json(['success' => true]);
    }

    /** AJAX: Get employee CNC settings (target, incentive_rate, payment_type) */
    public function getEmployeeSettings(Request $request)
    {
        $request->validate(['employee_id' => 'required|integer|exists:employees,id']);

        $emp = Employee::findOrFail($request->employee_id);

        return response()->json([
            'cnc_payment_type'     => $emp->cnc_payment_type,
            'cnc_target_per_shift' => $emp->cnc_target_per_shift,
            'cnc_incentive_rate'   => (float) $emp->cnc_incentive_rate,
        ]);
    }

    /** AJAX: Parts filtered by company */
    public function getPartsByCompany(Request $request)
    {
        $request->validate(['company_id' => 'required|integer|exists:companies,id']);

        $parts = Part::active()
            ->where('company_id', $request->company_id)
            ->orderBy('part_number')
            ->get(['id', 'part_number', 'part_name']);

        return response()->json($parts);
    }
}
