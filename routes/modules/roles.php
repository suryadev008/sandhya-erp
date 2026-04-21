<?php

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::middleware('role:admin')->group(function () {
    Route::get('roles',           [RoleController::class, 'index'])->name('roles.index');
    Route::get('roles/data',      [RoleController::class, 'getData'])->name('roles.data');
    Route::post('roles',          [RoleController::class, 'store'])->name('roles.store');
    Route::put('roles/{role}',    [RoleController::class, 'update'])->name('roles.update');
    Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
});
