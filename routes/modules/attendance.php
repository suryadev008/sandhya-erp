<?php

use App\Http\Controllers\AttendanceController;

Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
