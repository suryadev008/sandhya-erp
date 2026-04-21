<?php

use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:view attendance')->group(function () {
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
});
