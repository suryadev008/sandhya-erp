<?php

use App\Http\Controllers\OwnerCompanyController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::middleware('permission:view my-company')->group(function () {
        Route::get('my-company',            [OwnerCompanyController::class, 'index'])->name('my-company.index');
        Route::get('my-company/create',     [OwnerCompanyController::class, 'create'])->name('my-company.create');
        Route::get('my-company/{my_company}', [OwnerCompanyController::class, 'show'])->name('my-company.show');
    });

    Route::middleware('permission:edit my-company')->group(function () {
        Route::post('my-company',                                    [OwnerCompanyController::class, 'store'])->name('my-company.store');
        Route::get('my-company/{my_company}/edit',                   [OwnerCompanyController::class, 'edit'])->name('my-company.edit');
        Route::put('my-company/{my_company}',                        [OwnerCompanyController::class, 'update'])->name('my-company.update');
        Route::patch('my-company/{my_company}',                      [OwnerCompanyController::class, 'update']);
        Route::patch('my-company/{my_company}/toggle-status',        [OwnerCompanyController::class, 'toggleStatus'])->name('my-company.toggle-status');
        Route::delete('my-company/{my_company}',                     [OwnerCompanyController::class, 'destroy'])->name('my-company.destroy');
    });

});
