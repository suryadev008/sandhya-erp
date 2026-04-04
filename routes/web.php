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
            $validated = $request->validate([
                'sidebar_skin'      => 'nullable|string|in:sidebar-dark-primary,sidebar-dark-warning,sidebar-dark-danger,sidebar-dark-success,sidebar-dark-info,sidebar-light-primary,sidebar-light-warning,sidebar-light-danger,sidebar-light-success,sidebar-light-info',
                'navbar_skin'       => 'nullable|string|in:navbar-dark,navbar-light,navbar-primary,navbar-warning,navbar-danger,navbar-success,navbar-info',
                'accent_color'      => 'nullable|string|in:accent-primary,accent-warning,accent-danger,accent-success,accent-info',
                'dark_mode'         => 'nullable|boolean',
                'sidebar_collapsed' => 'nullable|boolean',
            ]);
            $request->user()->update([
                'theme_settings' => $validated
            ]);
            return response()->json(['success' => true]);
        })->name('theme.settings.update');    });


/* |--------- | Web Routes |----- */

Route::middleware(['auth', 'admin'])->prefix('master')->group(function () {

    // require __DIR__ . '/modules/dashboard.php';
    require __DIR__ . '/modules/machine_types.php';
    require __DIR__ . '/modules/machines.php';
    require __DIR__ . '/modules/operations.php';
    require __DIR__ . '/modules/parts.php';
    require __DIR__ . '/modules/companies.php';
    require __DIR__ . '/modules/contacts.php';

});

Route::middleware(['auth', 'admin'])->prefix('payroll')->group(function () {

    require __DIR__ . '/modules/employees.php';
    require __DIR__ . '/modules/salaries.php';
    require __DIR__ . '/modules/payrolls.php';
    require __DIR__ . '/modules/attendance.php';

});

require __DIR__ . '/modules/my_company.php';

Route::middleware(['auth', 'admin'])->prefix('register')->group(function () {
    require __DIR__ . '/modules/productions.php';
});

require __DIR__ . '/auth.php';
