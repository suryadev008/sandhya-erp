<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PartController;

Route::middleware(['auth'])->group(function () {
    Route::resource('parts', PartController::class);
});
