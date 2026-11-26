<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RentalController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:web')->group(function (): void {
    Route::resource('rentals', RentalController::class)->except(['destroy']);
    Route::post('/rentals/{rental}/return', [RentalController::class, 'returnVehicle'])
        ->name('rentals.return');
    Route::post('/rentals/{rental}/cancel', [RentalController::class, 'cancel'])
        ->name('rentals.cancel');

    Route::resource('payments', PaymentController::class);
    Route::post('/payments/{payment}/mark-paid', [PaymentController::class, 'markPaid'])
        ->name('payments.mark-paid');
    Route::post('/payments/{payment}/mark-failed', [PaymentController::class, 'markFailed'])
        ->name('payments.mark-failed');
});
