<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;

Route::middleware(['auth'])->group(function () {
    Route::get('employees/next-code', [EmployeeController::class, 'nextCode'])->name('employees.next-code');
    Route::resource('employees', EmployeeController::class);

    // Salary routes
    Route::post('employees/{id}/salary',         [EmployeeController::class, 'storeSalary'])->name('employees.salary.store');
    Route::get('employees/{id}/salary-history',  [EmployeeController::class, 'salaryHistory'])->name('employees.salary.history');

    // Operation rate routes
    Route::get('employees/{id}/operation-rates',             [EmployeeController::class, 'operationRates'])->name('employees.operation-rates.index');
    Route::post('employees/{id}/operation-rates',            [EmployeeController::class, 'storeOperationRate'])->name('employees.operation-rates.store');
    Route::delete('employees/{id}/operation-rates/{rateId}', [EmployeeController::class, 'destroyOperationRate'])->name('employees.operation-rates.destroy');
});
