<?php

use App\Http\Controllers\LatheProductionController;

Route::prefix('lathe-productions')->name('lathe-productions.')->group(function () {
    Route::get('/create',              [LatheProductionController::class, 'create'])->name('create');
    Route::post('/store',              [LatheProductionController::class, 'store'])->name('store');
    Route::get('/parts-by-company',   [LatheProductionController::class, 'getPartsByCompany'])->name('parts-by-company');
});
