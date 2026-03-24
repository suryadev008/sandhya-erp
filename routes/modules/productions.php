<?php

use App\Http\Controllers\LatheProductionController;

Route::prefix('lathe-productions')->name('lathe-productions.')->group(function () {
    Route::get('/',                      [LatheProductionController::class, 'index'])->name('index');
    Route::get('/create',                [LatheProductionController::class, 'create'])->name('create');
    Route::post('/store',                [LatheProductionController::class, 'store'])->name('store');
    Route::get('/parts-by-company',      [LatheProductionController::class, 'getPartsByCompany'])->name('parts-by-company');
    Route::get('/operations-by-company', [LatheProductionController::class, 'getOperationsByCompany'])->name('operations-by-company');
    Route::get('/operation-rate',        [LatheProductionController::class, 'getOperationRate'])->name('operation-rate');
    Route::get('/{employeeId}',          [LatheProductionController::class, 'show'])->name('show');
    Route::put('/{id}',                  [LatheProductionController::class, 'update'])->name('update');
    Route::delete('/{id}',               [LatheProductionController::class, 'destroy'])->name('destroy');
});
