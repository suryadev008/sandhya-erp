<?php

use App\Http\Controllers\CncProductionController;
use App\Http\Controllers\LatheProductionController;
use Illuminate\Support\Facades\Route;

// ── Lathe Productions ─────────────────────────────────────────────────────────
Route::prefix('lathe-productions')->name('lathe-productions.')->group(function () {

    Route::middleware('permission:view productions')->group(function () {
        Route::get('/',                      [LatheProductionController::class, 'index'])->name('index');
        Route::get('/parts-by-company',      [LatheProductionController::class, 'getPartsByCompany'])->name('parts-by-company');
        Route::get('/operations-by-company', [LatheProductionController::class, 'getOperationsByCompany'])->name('operations-by-company');
        Route::get('/operation-rate',        [LatheProductionController::class, 'getOperationRate'])->name('operation-rate');
    });

    Route::middleware('permission:create productions')->group(function () {
        Route::get('/create',   [LatheProductionController::class, 'create'])->name('create');
        Route::post('/store',   [LatheProductionController::class, 'store'])->name('store');
    });

    Route::middleware('permission:view productions')->group(function () {
        Route::get('/{employeeId}', [LatheProductionController::class, 'show'])->name('show');
    });

    Route::middleware('permission:edit productions')->group(function () {
        Route::put('/{id}', [LatheProductionController::class, 'update'])->name('update');
    });

    Route::middleware('permission:delete productions')->group(function () {
        Route::delete('/{id}', [LatheProductionController::class, 'destroy'])->name('destroy');
    });
});

// ── CNC Productions ───────────────────────────────────────────────────────────
Route::prefix('cnc-productions')->name('cnc-productions.')->group(function () {

    Route::middleware('permission:view productions')->group(function () {
        Route::get('/',                  [CncProductionController::class, 'index'])->name('index');
        Route::get('/parts-by-company',  [CncProductionController::class, 'getPartsByCompany'])->name('parts-by-company');
        Route::get('/employee-settings', [CncProductionController::class, 'getEmployeeSettings'])->name('employee-settings');
    });

    Route::middleware('permission:create productions')->group(function () {
        Route::get('/create', [CncProductionController::class, 'create'])->name('create');
        Route::post('/store', [CncProductionController::class, 'store'])->name('store');
    });

    Route::middleware('permission:view productions')->group(function () {
        Route::get('/{employeeId}', [CncProductionController::class, 'show'])->name('show');
    });

    Route::middleware('permission:edit productions')->group(function () {
        Route::put('/{id}', [CncProductionController::class, 'update'])->name('update');
    });

    Route::middleware('permission:delete productions')->group(function () {
        Route::delete('/{id}', [CncProductionController::class, 'destroy'])->name('destroy');
    });
});
