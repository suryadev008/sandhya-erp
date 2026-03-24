<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::prefix('user')->group(function () {
        Route::get('/profile', [ProfileController::class , 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class , 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class , 'destroy'])->name('profile.destroy');
    });
    // Route for saving User Theme Preferences to the database
    Route::post('/theme-settings', function (\Illuminate\Http\Request $request) {
            $request->user()->update([
                'theme_settings' => $request->all()
            ]);
            return response()->json(['success' => true]);
        })->name('theme.settings.update');    });


/* |--------- | Web Routes |----- */

Route::middleware(['auth'])->prefix('master')->group(function () {

    // require __DIR__ . '/modules/dashboard.php';
    require __DIR__ . '/modules/machine_types.php';
    require __DIR__ . '/modules/machines.php';
    require __DIR__ . '/modules/operations.php';
    require __DIR__ . '/modules/parts.php';
    require __DIR__ . '/modules/companies.php';
    require __DIR__ . '/modules/contacts.php';
    require __DIR__ . '/modules/employees.php';
    require __DIR__ . '/modules/salaries.php';
    require __DIR__ . '/modules/payrolls.php';

});

Route::middleware(['auth'])->prefix('register')->group(function () {
    require __DIR__ . '/modules/productions.php';
});

require __DIR__ . '/auth.php';
