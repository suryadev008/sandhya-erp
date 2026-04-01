<?php

use App\Http\Controllers\MachineController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('machines', MachineController::class);
});