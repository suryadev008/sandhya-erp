<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OwnerCompanyController;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('my-company', OwnerCompanyController::class)->parameters([
        'my-company' => 'my_company'
    ]);
    Route::patch('my-company/{my_company}/toggle-status', [OwnerCompanyController::class, 'toggleStatus'])->name('my-company.toggle-status');
});
