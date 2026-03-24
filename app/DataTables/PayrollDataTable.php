<?php

namespace App\DataTables;

use App\Models\Employee;
use Yajra\DataTables\Facades\DataTables;

class PayrollDataTable
{
    public function ajax()
    {
        $query = Employee::with('currentSalary')->orderBy('id');

        $status = request('status');
        if ($status !== null && $status !== '') {
            $query->where('status', $status);
        }

        $type = request('type');
        if ($type !== null && $type !== '') {
            $query->where('employee_type', $type);
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('emp_code', fn($e) => '<a href="' . route('payrolls.show', $e->id) . '" class="font-weight-bold">' . e($e->emp_code) . '</a>')
            ->editColumn('name', fn($e) => '<a href="' . route('payrolls.show', $e->id) . '">' . e($e->name) . '</a>')
            ->editColumn('employee_type', fn($e) => ucfirst($e->employee_type))
            ->addColumn('per_day', fn($e) => $e->currentSalary ? '₹ ' . number_format($e->currentSalary->per_day, 2) : '<span class="text-muted">—</span>')
            ->addColumn('per_month', fn($e) => $e->currentSalary ? '₹ ' . number_format($e->currentSalary->per_month, 2) : '<span class="text-muted">—</span>')
            ->editColumn('status', fn($e) => ucfirst($e->status))
            ->addColumn('action', fn($e) => '<a href="' . route('payrolls.show', $e->id) . '" class="btn btn-sm btn-info"><i class="fas fa-history"></i> History</a>')
            ->rawColumns(['emp_code', 'name', 'per_day', 'per_month', 'action'])
            ->toJson();
    }
}
