<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OperationController;

Route::middleware(['auth'])->group(function () {
    Route::resource('operations', OperationController::class);
});
