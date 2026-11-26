<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:web')->group(function (): void {
    Route::resource('customers', CustomerController::class)->except(['destroy']);
    Route::resource('vehicles', VehicleController::class)->except(['destroy']);

    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])
        ->middleware('admin')
        ->name('customers.destroy');

    Route::delete('/vehicles/{vehicle}', [VehicleController::class, 'destroy'])
        ->middleware('admin')
        ->name('vehicles.destroy');
});
