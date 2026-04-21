<?php

use App\Http\Controllers\MachineController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:view machines')->group(function () {
    Route::get('machines',              [MachineController::class, 'index'])->name('machines.index');
    Route::get('machines/data',         [MachineController::class, 'getData'])->name('machines.data');
    Route::get('machines/{machine}',    [MachineController::class, 'show'])->name('machines.show');
});

Route::middleware('permission:create machines')->group(function () {
    Route::get('machines/create',   [MachineController::class, 'create'])->name('machines.create');
    Route::post('machines',         [MachineController::class, 'store'])->name('machines.store');
});

Route::middleware('permission:edit machines')->group(function () {
    Route::get('machines/{machine}/edit', [MachineController::class, 'edit'])->name('machines.edit');
    Route::put('machines/{machine}',      [MachineController::class, 'update'])->name('machines.update');
    Route::patch('machines/{machine}',    [MachineController::class, 'update']);
});

Route::middleware('permission:delete machines')->group(function () {
    Route::delete('machines/{machine}', [MachineController::class, 'destroy'])->name('machines.destroy');
});
