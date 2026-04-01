<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;

Route::middleware(['auth'])->group(function () {
    Route::get('companies/verify-gst', [CompanyController::class, 'verifyGst'])
        ->middleware('throttle:10,1')   // max 10 GST verify requests per minute
        ->name('companies.verify-gst');
    Route::resource('companies', CompanyController::class);
});
