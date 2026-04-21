<?php

use App\Http\Controllers\SalaryController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:view salaries')->group(function () {
    Route::get('salaries',      [SalaryController::class, 'index'])->name('salaries.index');
    Route::get('salaries/data', [SalaryController::class, 'getData'])->name('salaries.data');
});
