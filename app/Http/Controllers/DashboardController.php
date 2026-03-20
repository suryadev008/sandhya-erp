<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use App\Models\EmployeeSalary;
use App\Models\Machine;
use App\Models\Operation;
use App\Models\Part;

class DashboardController extends Controller
{
    public function index()
    {
        // Employee stats
        $totalEmployees    = Employee::count();
        $activeEmployees   = Employee::where('status', 'active')->count();
        $inactiveEmployees = Employee::where('status', 'inactive')->count();
        $terminatedEmployees = Employee::where('status', 'terminated')->count();

        // Employee type breakdown
        $latheEmployees = Employee::where('employee_type', 'lathe')->where('status', 'active')->count();
        $cncEmployees   = Employee::where('employee_type', 'cnc')->where('status', 'active')->count();
        $bothEmployees  = Employee::where('employee_type', 'both')->where('status', 'active')->count();

        // Salary stats
        $employeesWithSalary    = EmployeeSalary::distinct('employee_id')->count('employee_id');
        $employeesWithoutSalary = $totalEmployees - $employeesWithSalary;

        // Monthly payroll estimate (current active salaries of active employees)
        $monthlyPayroll = EmployeeSalary::whereIn('employee_id',
            Employee::where('status', 'active')->pluck('id')
        )
        ->where('effect_from', '<=', now()->toDateString())
        ->orderBy('employee_id')
        ->orderBy('effect_from', 'desc')
        ->get()
        ->unique('employee_id')
        ->sum('per_month');

        // Other modules
        $totalCompanies  = Company::count();
        $activeCompanies = Company::where('is_active', true)->count();
        $totalMachines   = Machine::count();
        $activeMachines  = Machine::where('is_active', true)->count();
        $totalOperations  = Operation::count();
        $activeOperations = Operation::where('is_active', true)->count();
        $totalParts       = Part::count();
        $activeParts      = Part::where('is_active', true)->count();

        // Recent employees (last 6)
        $recentEmployees = Employee::with('currentSalary')
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard', compact(
            'totalEmployees', 'activeEmployees', 'inactiveEmployees', 'terminatedEmployees',
            'latheEmployees', 'cncEmployees', 'bothEmployees',
            'employeesWithSalary', 'employeesWithoutSalary', 'monthlyPayroll',
            'totalCompanies', 'activeCompanies',
            'totalMachines', 'activeMachines',
            'totalOperations', 'activeOperations', 'totalParts', 'activeParts',
            'recentEmployees'
        ));
    }
}
