<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use App\Models\LatheProduction;
use App\Models\Machine;
use App\Models\Operation;
use App\Models\Part;
use Illuminate\Http\Request;

class LatheProductionController extends Controller
{
    public function create()
    {
        $employees  = Employee::whereIn('employee_type', ['lathe', 'both'])
                        ->where('status', 'active')
                        ->orderBy('name')
                        ->get(['id', 'emp_code', 'name']);

        $companies  = Company::active()->orderBy('company_name')->get(['id', 'company_name']);

        $operations = Operation::active()
                        ->whereIn('applicable_for', ['lathe', 'both'])
                        ->orderBy('operation_name')
                        ->get(['id', 'operation_name', 'price']);

        $machines   = Machine::active()
                        ->where('machine_type', 'lathe')
                        ->orderBy('machine_name')
                        ->get(['id', 'machine_name', 'machine_number']);

        return view('lathe_productions.create', compact('employees', 'companies', 'operations', 'machines'));
    }

    /**
     * Fetch parts filtered by company — called via AJAX
     */
    public function getPartsByCompany(Request $request)
    {
        $parts = Part::active()
            ->where('company_id', $request->company_id)
            ->orderBy('part_number')
            ->get(['id', 'part_number', 'part_name']);

        return response()->json($parts);
    }

    /**
     * Store multiple production rows in one submission
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id'     => 'required|exists:employees,id',
            'date'            => 'required|date|before_or_equal:today',
            'shift'           => 'required|in:day,night,A,B,general',
            'rows'            => 'required|array|min:1',
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
            $operation = Operation::find($row['operation_id']);
            $rate      = $operation ? (float) $operation->price : 0;
            $qty       = (int) $row['qty'];
            $amount    = $rate * $qty;

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
