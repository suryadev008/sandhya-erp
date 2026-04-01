<?php

use App\Http\Controllers\MachineTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('machine-types', MachineTypeController::class)->only(['index', 'store', 'edit', 'update', 'destroy']);
});
