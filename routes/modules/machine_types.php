<?php

use App\Http\Controllers\MachineTypeController;

Route::resource('machine-types', MachineTypeController::class)->only(['index', 'store', 'edit', 'update', 'destroy']);
