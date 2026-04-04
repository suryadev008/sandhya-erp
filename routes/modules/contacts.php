<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContactCategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('contacts', ContactController::class)->only(['index', 'store', 'show', 'edit', 'update', 'destroy']);
    Route::resource('contact-categories', ContactCategoryController::class)->only(['index', 'store', 'update', 'destroy']);
});
