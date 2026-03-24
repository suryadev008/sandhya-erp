<?php

use App\Http\Controllers\LathePayslipController;
use App\Http\Controllers\PayrollController;

Route::prefix('payrolls')->name('payrolls.')->group(function () {
    Route::get('/',                                          [PayrollController::class, 'index'])->name('index');
    Route::get('/{id}',                                      [PayrollController::class, 'show'])->name('show');
    Route::post('/{id}/generate',                            [PayrollController::class, 'generate'])->name('generate');
    Route::post('/{payrollId}/extra-payment',                [PayrollController::class, 'addExtraPayment'])->name('extra-payment.store');
    Route::delete('/{payrollId}/extra-payment/{extraId}',    [PayrollController::class, 'removeExtraPayment'])->name('extra-payment.destroy');
    Route::post('/{payrollId}/deduction',                    [PayrollController::class, 'updateDeduction'])->name('deduction.update');
    Route::post('/{payrollId}/status',                       [PayrollController::class, 'updateStatus'])->name('status.update');
    Route::get('/{payrollId}/detail',                                       [PayrollController::class, 'detail'])->name('detail');

    // Lathe payslip routes
    Route::prefix('{employeeId}/lathe-slip')->name('lathe-slip.')->group(function () {
        Route::get('/',                                [LathePayslipController::class, 'show'])->name('show');
        Route::post('/save',                           [LathePayslipController::class, 'save'])->name('save');
        Route::post('/{payrollId}/extra',              [LathePayslipController::class, 'addExtra'])->name('extra.store');
        Route::delete('/{payrollId}/extra/{extraId}',  [LathePayslipController::class, 'removeExtra'])->name('extra.destroy');
        Route::post('/{payrollId}/deduction',          [LathePayslipController::class, 'updateDeduction'])->name('deduction');
        Route::post('/{payrollId}/status',             [LathePayslipController::class, 'updateStatus'])->name('status');
        Route::get('/{payrollId}/pdf',                 [LathePayslipController::class, 'pdf'])->name('pdf');
    });
});
