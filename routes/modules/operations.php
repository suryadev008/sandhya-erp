<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OperationController;

Route::middleware(['auth'])->group(function () {
    Route::resource('operations', OperationController::class);
    Route::post('operations/{id}/prices',       [OperationController::class, 'storePrice'])->name('operations.price.store');
    Route::get('operations/{id}/price-history', [OperationController::class, 'priceHistory'])->name('operations.price.history');
});
