<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;

Route::middleware(['auth'])->group(function () {
    Route::resource('companies', CompanyController::class);
});
