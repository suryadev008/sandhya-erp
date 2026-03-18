<?php

use App\Http\Controllers\MachineController;
use Illuminate\Support\Facades\Route;

Route::prefix('machines')->name('machines.')->group(function () {
    Route::get('/', [MachineController::class , 'index'])->name('index');
// Route::get('/create', [MachineController::class , 'create'])->name('create');
// Route::post('/', [MachineController::class , 'store'])->name('store');
// Route::get('/{id}', [MachineController::class , 'show'])->name('show');
// Route::get('/{id}/edit', [MachineController::class , 'edit'])->name('edit');
// Route::put('/{id}', [MachineController::class , 'update'])->name('update');
// Route::delete('/{id}', [MachineController::class , 'destroy'])->name('destroy');
});