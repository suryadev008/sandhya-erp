<?php

use App\Http\Controllers\MachineTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:view machine-types')->group(function () {
    Route::get('machine-types',      [MachineTypeController::class, 'index'])->name('machine-types.index');
    Route::get('machine-types/data', [MachineTypeController::class, 'getData'])->name('machine-types.data');
});

Route::middleware('permission:create machine-types')->group(function () {
    Route::post('machine-types', [MachineTypeController::class, 'store'])->name('machine-types.store');
});

Route::middleware('permission:edit machine-types')->group(function () {
    Route::get('machine-types/{machine_type}/edit', [MachineTypeController::class, 'edit'])->name('machine-types.edit');
    Route::put('machine-types/{machine_type}',      [MachineTypeController::class, 'update'])->name('machine-types.update');
    Route::patch('machine-types/{machine_type}',    [MachineTypeController::class, 'update']);
});

Route::middleware('permission:delete machine-types')->group(function () {
    Route::delete('machine-types/{machine_type}', [MachineTypeController::class, 'destroy'])->name('machine-types.destroy');
});
