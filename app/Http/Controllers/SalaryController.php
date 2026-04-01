<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeSalary;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function index()
    {
        return view('salaries.index');
    }

    public function getData()
    {
        $employees = Employee::with(['currentSalary'])
            ->whereNull('deleted_at')
            ->orderBy('name')
            ->get()
            ->map(function ($emp) {
                return [
                    'id'         => $emp->id,
                    'emp_code'   => $emp->emp_code,
                    'name'       => $emp->name,
                    'type'       => ucfirst($emp->employee_type),
                    'status'     => $emp->status,
                    'per_day'    => $emp->currentSalary ? number_format($emp->currentSalary->per_day, 2) : null,
                    'per_month'  => $emp->currentSalary ? number_format($emp->currentSalary->per_month, 2) : null,
                    'effect_from'=> $emp->currentSalary ? $emp->currentSalary->effect_from->format('d M Y') : null,
                    'remark'     => $emp->currentSalary->remark ?? null,
                ];
            });

        return response()->json(['success' => true, 'data' => $employees]);
    }
}
