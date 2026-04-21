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
                return ucfirst($emp->status);
            })
            ->editColumn('employee_type', function ($emp) {
                return ucfirst($emp->employee_type);
            })
            ->editColumn('joining_date', function ($emp) {
                return $emp->joining_date ? $emp->joining_date->format('d-m-Y') : '—';
            })
            ->addColumn('action', function ($emp) {
                $id   = (int) $emp->id;
                $html = '';
                if (auth()->user()->can('edit employees')) {
                    $html .= '<button type="button" class="btn btn-warning btn-sm edit-btn" data-id="' . $id . '" data-toggle="modal" data-target="#edit-module-popup" title="Edit"><i class="fas fa-edit"></i></button> ';
                }
                if (auth()->user()->can('delete employees')) {
                    $html .= '<button class="btn btn-sm btn-danger delete-btn" data-id="' . $id . '" title="Delete"><i class="fas fa-trash"></i></button>';
                }
                return $html ?: '—';
            })
            ->rawColumns(['emp_code', 'name', 'action'])
            ->toJson();
    }
}
