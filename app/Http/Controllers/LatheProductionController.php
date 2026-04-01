<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use App\Models\EmployeeOperationRate;
use App\Models\LatheProduction;
use App\Models\Machine;
use App\Models\MachineType;
use App\Models\Operation;
use App\Models\OperationPrice;
use App\Models\Part;
use App\Models\Payroll;
use Illuminate\Http\Request;

class LatheProductionController extends Controller
{
    /** Employee list DataTable page */
    public function index()
    {
        $employees = Employee::whereIn('employee_type', ['lathe', 'both'])
            ->orderBy('emp_code')
            ->get(['id', 'emp_code', 'name', 'employee_type', 'status']);

        return view('lathe_productions.index', compact('employees'));
    }

    /** Show all entries for one employee with filters */
    public function show(Request $request, $employeeId)
    {
        $employee = Employee::findOrFail($employeeId);

        // Validate & clamp year/month to safe ranges
        $year  = max(2020, min((int) now()->year, (int) ($request->year  ?? now()->year)));
        $month = max(1,    min(12,                (int) ($request->month ?? now()->month)));
        $date  = $request->filled('date') ? $request->date : null;

        // Validate date format if provided
        if ($date && !\Illuminate\Support\Carbon::canBeCreatedFromFormat($date, 'Y-m-d')) {
            $date = null;
        }

        $query = LatheProduction::with(['company', 'part', 'operation', 'machine'])
            ->where('employee_id', $employeeId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month);

        if ($date) {
            $query->whereDate('date', $date);
        }

        $entries = $query->orderBy('date')->orderBy('id')->get();

        // Check if payroll is generated for selected month → lock editing
        $payroll = Payroll::where('employee_id', $employeeId)
            ->where('month', $month)
            ->where('year', $year)
            ->first();

        $locked = $payroll && in_array($payroll->status, ['approved', 'paid']);

        $companies  = Company::active()->orderBy('company_name')->get(['id', 'company_name']);
        $lathTypeId = MachineType::whereRaw('LOWER(type_name) = ?', [config('company.lathe_machine_type')])->value('id');
        $machines   = Machine::active()
            ->when($lathTypeId, fn($q) => $q->where('machine_type_id', $lathTypeId))
            ->orderBy('machine_name')
            ->get(['id', 'machine_name', 'machine_number']);

        $years = range(now()->year, 2020);

        return view('lathe_productions.show', compact(
            'employee', 'entries', 'year', 'month', 'date',
            'payroll', 'locked', 'companies', 'machines', 'years'
        ));
    }

    /** Update a single entry */
    public function update(Request $request, $id)
    {
        $entry = LatheProduction::findOrFail($id);

        // Prevent edit if payroll approved/paid
        $payroll = Payroll::where('employee_id', $entry->employee_id)
            ->where('month', $entry->date->month)
            ->where('year', $entry->date->year)
            ->first();

        if ($payroll && in_array($payroll->status, ['approved', 'paid'])) {
            return response()->json(['error' => 'Payroll is locked for this month.'], 403);
        }

        $request->validate([
            'date'         => 'required|date|before_or_equal:today',
            'shift'        => 'required|in:day,night,A,B,general',
            'company_id'   => 'required|exists:companies,id',
            'part_id'      => 'required|exists:parts,id',
            'operation_id' => 'required|exists:operations,id',
            'qty'          => 'required|integer|min:1',
            'machine_id'   => 'nullable|exists:machines,id',
            'remarks'      => 'nullable|string|max:255',
        ]);

        // Employee-specific rate takes priority over global operation price
        $rate = EmployeeOperationRate::rateFor($entry->employee_id, (int) $request->operation_id, $request->date);
        if ($rate === 0.0) {
            $rate = (float) (OperationPrice::where('operation_id', $request->operation_id)
                ->where('applicable_from', '<=', $request->date)
                ->orderBy('applicable_from', 'desc')
                ->value('price') ?? 0);
        }

        $entry->update([
            'date'         => $request->date,
            'shift'        => $request->shift,
            'company_id'   => $request->company_id,
            'part_id'      => $request->part_id,
            'operation_id' => $request->operation_id,
            'machine_id'   => $request->machine_id,
            'qty'          => $request->qty,
            'rate'         => $rate,
            'amount'       => $rate * $request->qty,
            'remarks'      => $request->remarks,
        ]);

        return response()->json(['success' => true, 'entry' => $entry->fresh(['company','part','operation','machine'])]);
    }

    /** Delete a single entry */
    public function destroy($id)
    {
        $entry = LatheProduction::findOrFail($id);

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

    public function create()
    {
        $employees = Employee::whereIn('employee_type', ['lathe', 'both'])
            ->where('status', 'active')
            ->orderBy('id')
            ->get(['id', 'emp_code', 'name']);

        $companies = Company::active()->orderBy('company_name')->get(['id', 'company_name']);

        $lathTypeId = MachineType::whereRaw('LOWER(type_name) = ?', [config('company.lathe_machine_type')])->value('id');
        $machines   = Machine::active()
            ->when($lathTypeId, fn($q) => $q->where('machine_type_id', $lathTypeId))
            ->orderBy('machine_name')
            ->get(['id', 'machine_name', 'machine_number']);

        return view('lathe_productions.create', compact('employees', 'companies', 'machines'));
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

    /** AJAX: Operations filtered by company (lathe/both only) */
    public function getOperationsByCompany(Request $request)
    {
        $request->validate(['company_id' => 'required|integer|exists:companies,id']);

        $operations = Operation::active()
            ->whereIn('applicable_for', ['lathe', 'both'])
            ->where('company_id', $request->company_id)
            ->orderBy('operation_name')
            ->get(['id', 'operation_name']);

        return response()->json($operations);
    }

    /** AJAX: Price applicable on a given date for an operation
     *  Priority: employee-specific rate → global operation price → 0
     */
    public function getOperationRate(Request $request)
    {
        $request->validate([
            'operation_id' => 'required|integer|exists:operations,id',
            'employee_id'  => 'nullable|integer|exists:employees,id',
            'date'         => 'nullable|date|before_or_equal:today',
        ]);

        $date        = $request->input('date', now()->toDateString());
        $operationId = (int) $request->operation_id;
        $employeeId  = $request->filled('employee_id') ? (int) $request->employee_id : null;

        // 1. Employee-specific rate (highest priority)
        if ($employeeId) {
            $empRate = EmployeeOperationRate::where('employee_id', $employeeId)
                ->where('operation_id', $operationId)
                ->where('applicable_from', '<=', $date)
                ->orderBy('applicable_from', 'desc')
                ->value('rate');

            if ($empRate !== null) {
                return response()->json(['rate' => (float) $empRate, 'source' => 'employee']);
            }
        }

        // 2. Global operation price (fallback)
        $price = OperationPrice::where('operation_id', $operationId)
            ->where('applicable_from', '<=', $date)
            ->orderBy('applicable_from', 'desc')
            ->value('price');

        return response()->json(['rate' => $price !== null ? (float) $price : 0, 'source' => 'global']);
    }

    /** Store multiple production rows */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id'         => 'required|exists:employees,id',
            'date'                => 'required|date|before_or_equal:today',
            'shift'               => 'required|in:day,night,A,B,general',
            'rows'                => 'required|array|min:1',
            'rows.*.company_id'   => 'required|exists:companies,id',
            'rows.*.part_id'      => 'required|exists:parts,id',
            'rows.*.operation_id' => 'required|exists:operations,id',
            'rows.*.qty'          => 'required|integer|min:1',
        ]);

        $employeeId = $request->employee_id;
        $date       = $request->date;
        $shift      = $request->shift;
        $machineId  = $request->machine_id ?: null;
        $createdBy  = auth()->id();
        $now        = now();

        $inserts = [];
        foreach ($request->rows as $row) {
            // Employee-specific rate takes priority over global operation price
            $rate = EmployeeOperationRate::rateFor((int) $employeeId, (int) $row['operation_id'], $date);
            if ($rate === 0.0) {
                $rate = (float) (OperationPrice::where('operation_id', $row['operation_id'])
                    ->where('applicable_from', '<=', $date)
                    ->orderBy('applicable_from', 'desc')
                    ->value('price') ?? 0);
            }

            $qty    = (int) $row['qty'];
            $amount = $rate * $qty;

            $inserts[] = [
                'employee_id'  => $employeeId,
                'machine_id'   => $machineId,
                'date'         => $date,
                'shift'        => $shift,
                'company_id'   => $row['company_id'],
                'part_id'      => $row['part_id'],
                'operation_id' => $row['operation_id'],
                'qty'          => $qty,
                'rate'         => $rate,
                'amount'       => $amount,
                'remarks'      => $row['remarks'] ?? null,
                'created_by'   => $createdBy,
                'created_at'   => $now,
                'updated_at'   => $now,
            ];
        }

        LatheProduction::insert($inserts);

        return redirect()->route('lathe-productions.create')
            ->with('success', count($inserts) . ' production record(s) saved successfully.');
    }
}
