<?php

namespace App\DataTables;

use App\Models\Employee;
use Yajra\DataTables\Facades\DataTables;

class EmployeesDataTable
{
    public function ajax()
    {
        $query = Employee::query()->orderBy('id', 'desc');

        $status = request('status');
        if ($status !== null && $status !== '') {
            $query->where('status', $status);
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('emp_code', function ($emp) {
                return '<a href="' . route('employees.show', $emp->id) . '">' . e($emp->emp_code) . '</a>';
            })
            ->editColumn('name', function ($emp) {
                return '<a href="' . route('employees.show', $emp->id) . '">' . e($emp->name) . '</a>';
            })
            ->editColumn('status', function ($emp) {
                $colors = [
                    'active'     => 'success',
                    'inactive'   => 'secondary',
                    'terminated' => 'danger',
                ];
                $color = $colors[$emp->status] ?? 'secondary';
                return '<span class="badge badge-' . $color . '">' . ucfirst($emp->status) . '</span>';
            })
            ->editColumn('employee_type', function ($emp) {
                return ucfirst($emp->employee_type);
            })
            ->addColumn('action', function ($emp) {
                return '
                    <button type="button" class="btn btn-warning btn-sm edit-btn" data-id="' . $emp->id . '" data-toggle="modal" data-target="#edit-module-popup">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="' . $emp->id . '">
                        <i class="fas fa-trash"></i>
                    </button>
                ';
            })
            ->rawColumns(['emp_code', 'name', 'action', 'status'])
            ->toJson();
    }
}
