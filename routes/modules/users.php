<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('role:admin')->group(function () {
    Route::get('users',           [UserController::class, 'index'])->name('users.index');
    Route::get('users/data',      [UserController::class, 'getData'])->name('users.data');
    Route::post('users',          [UserController::class, 'store'])->name('users.store');
    Route::put('users/{user}',    [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});
