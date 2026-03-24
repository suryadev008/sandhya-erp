<?php

use App\Http\Controllers\ContactController;

Route::resource('contacts', ContactController::class)->only(['index', 'store', 'show', 'edit', 'update', 'destroy']);
