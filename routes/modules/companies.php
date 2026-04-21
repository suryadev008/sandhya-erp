<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DesignationController;
use Illuminate\Support\Facades\Route;

// Read
Route::middleware('permission:view companies')->group(function () {
    Route::get('companies',                  [CompanyController::class, 'index'])->name('companies.index');
    Route::get('companies/data',             [CompanyController::class, 'getData'])->name('companies.data');
    Route::get('companies/{company}',        [CompanyController::class, 'show'])->name('companies.show');
    Route::get('designations',               [DesignationController::class, 'index'])->name('designations.index');
    Route::get('companies/verify-gst',       [CompanyController::class, 'verifyGst'])
        ->middleware('throttle:10,1')
        ->name('companies.verify-gst');
});

// Create
Route::middleware('permission:create companies')->group(function () {
    Route::get('companies/create',  [CompanyController::class, 'create'])->name('companies.create');
    Route::post('companies',        [CompanyController::class, 'store'])->name('companies.store');
    Route::post('designations',     [DesignationController::class, 'store'])->name('designations.store');
});

// Edit
Route::middleware('permission:edit companies')->group(function () {
    Route::get('companies/{company}/edit',   [CompanyController::class, 'edit'])->name('companies.edit');
    Route::put('companies/{company}',        [CompanyController::class, 'update'])->name('companies.update');
    Route::patch('companies/{company}',      [CompanyController::class, 'update']);
});

// Delete
Route::middleware('permission:delete companies')->group(function () {
    Route::delete('companies/{company}',     [CompanyController::class, 'destroy'])->name('companies.destroy');
    Route::delete('designations/{id}',       [DesignationController::class, 'destroy'])->name('designations.destroy');
});
