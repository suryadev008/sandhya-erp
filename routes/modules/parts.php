<?php

use App\Http\Controllers\PartController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:view parts')->group(function () {
    Route::get('parts',          [PartController::class, 'index'])->name('parts.index');
    Route::get('parts/{part}',   [PartController::class, 'show'])->name('parts.show');
});

Route::middleware('permission:create parts')->group(function () {
    Route::get('parts/create',   [PartController::class, 'create'])->name('parts.create');
    Route::post('parts',         [PartController::class, 'store'])->name('parts.store');
});

Route::middleware('permission:edit parts')->group(function () {
    Route::get('parts/{part}/edit', [PartController::class, 'edit'])->name('parts.edit');
    Route::put('parts/{part}',      [PartController::class, 'update'])->name('parts.update');
    Route::patch('parts/{part}',    [PartController::class, 'update']);
});

Route::middleware('permission:delete parts')->group(function () {
    Route::delete('parts/{part}', [PartController::class, 'destroy'])->name('parts.destroy');
});
