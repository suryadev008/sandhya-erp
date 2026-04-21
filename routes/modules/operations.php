<?php

use App\Http\Controllers\OperationController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:view operations')->group(function () {
    Route::get('operations',                      [OperationController::class, 'index'])->name('operations.index');
    Route::get('operations/{operation}',          [OperationController::class, 'show'])->name('operations.show');
    Route::get('operations/{id}/price-history',   [OperationController::class, 'priceHistory'])->name('operations.price.history');
});

Route::middleware('permission:create operations')->group(function () {
    Route::get('operations/create',       [OperationController::class, 'create'])->name('operations.create');
    Route::post('operations',             [OperationController::class, 'store'])->name('operations.store');
    Route::post('operations/{id}/prices', [OperationController::class, 'storePrice'])->name('operations.price.store');
});

Route::middleware('permission:edit operations')->group(function () {
    Route::get('operations/{operation}/edit', [OperationController::class, 'edit'])->name('operations.edit');
    Route::put('operations/{operation}',      [OperationController::class, 'update'])->name('operations.update');
    Route::patch('operations/{operation}',    [OperationController::class, 'update']);
});

Route::middleware('permission:delete operations')->group(function () {
    Route::delete('operations/{operation}', [OperationController::class, 'destroy'])->name('operations.destroy');
});
