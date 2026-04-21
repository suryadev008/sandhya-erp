<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContactCategoryController;
use Illuminate\Support\Facades\Route;

// Read
Route::middleware('permission:view contacts')->group(function () {
    Route::get('contacts',                         [ContactController::class, 'index'])->name('contacts.index');
    Route::get('contacts/{contact}',               [ContactController::class, 'show'])->name('contacts.show');
    Route::get('contact-categories',               [ContactCategoryController::class, 'index'])->name('contact-categories.index');
});

// Create
Route::middleware('permission:create contacts')->group(function () {
    Route::post('contacts',                        [ContactController::class, 'store'])->name('contacts.store');
    Route::post('contact-categories',              [ContactCategoryController::class, 'store'])->name('contact-categories.store');
});

// Edit
Route::middleware('permission:edit contacts')->group(function () {
    Route::get('contacts/{contact}/edit',          [ContactController::class, 'edit'])->name('contacts.edit');
    Route::put('contacts/{contact}',               [ContactController::class, 'update'])->name('contacts.update');
    Route::patch('contacts/{contact}',             [ContactController::class, 'update']);
    Route::put('contact-categories/{category}',    [ContactCategoryController::class, 'update'])->name('contact-categories.update');
    Route::patch('contact-categories/{category}',  [ContactCategoryController::class, 'update']);
});

// Delete
Route::middleware('permission:delete contacts')->group(function () {
    Route::delete('contacts/{contact}',            [ContactController::class, 'destroy'])->name('contacts.destroy');
    Route::delete('contact-categories/{category}', [ContactCategoryController::class, 'destroy'])->name('contact-categories.destroy');
});
