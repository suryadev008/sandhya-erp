<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('contacts', ContactController::class)->only(['index', 'store', 'show', 'edit', 'update', 'destroy']);
});
