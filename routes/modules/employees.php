<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;

Route::middleware(['auth'])->group(function () {
    Route::get('employees/next-code', [EmployeeController::class, 'nextCode'])->name('employees.next-code');
    Route::resource('employees', EmployeeController::class);
    Route::post('employees/{id}/salary', [EmployeeController::class, 'storeSalary'])->name('employees.salary.store');
    Route::get('employees/{id}/salary-history', [EmployeeController::class, 'salaryHistory'])->name('employees.salary.history');
});
