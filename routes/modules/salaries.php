<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalaryController;

Route::get('salaries', [SalaryController::class, 'index'])->name('salaries.index');
Route::get('salaries/data', [SalaryController::class, 'getData'])->name('salaries.data');
