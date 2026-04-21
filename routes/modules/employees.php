<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:view employees')->group(function () {
    Route::get('employees/next-code',             [EmployeeController::class, 'nextCode'])->name('employees.next-code');
    Route::get('employees',                       [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('employees/{employee}',            [EmployeeController::class, 'show'])->name('employees.show');
    Route::get('employees/{id}/salary-history',   [EmployeeController::class, 'salaryHistory'])->name('employees.salary.history');
    Route::get('employees/{id}/operation-rates',  [EmployeeController::class, 'operationRates'])->name('employees.operation-rates.index');
});

Route::middleware('permission:create employees')->group(function () {
    Route::get('employees/create',        [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('employees',              [EmployeeController::class, 'store'])->name('employees.store');
    Route::post('employees/{id}/salary',  [EmployeeController::class, 'storeSalary'])->name('employees.salary.store');
    Route::post('employees/{id}/operation-rates', [EmployeeController::class, 'storeOperationRate'])->name('employees.operation-rates.store');
});

Route::middleware('permission:edit employees')->group(function () {
    Route::get('employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('employees/{employee}',      [EmployeeController::class, 'update'])->name('employees.update');
    Route::patch('employees/{employee}',    [EmployeeController::class, 'update']);
});

Route::middleware('permission:delete employees')->group(function () {
    Route::delete('employees/{employee}',                        [EmployeeController::class, 'destroy'])->name('employees.destroy');
    Route::delete('employees/{id}/operation-rates/{rateId}',     [EmployeeController::class, 'destroyOperationRate'])->name('employees.operation-rates.destroy');
});
