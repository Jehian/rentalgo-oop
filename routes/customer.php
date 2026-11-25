<?php

use App\Http\Controllers\Customer\CustomerAuthController;
use App\Http\Controllers\Customer\CustomerProfileController;
use App\Http\Controllers\Customer\CustomerRentalController;
use App\Http\Controllers\PortalController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PortalController::class, 'index'])->name('home');
Route::get('/catalog/{vehicle}', [PortalController::class, 'show'])->name('portal.vehicles.show');

Route::middleware('guest:customer')->group(function (): void {
    Route::get('/customer/login', [CustomerAuthController::class, 'showLogin'])->name('customer.login');
    Route::post('/customer/login', [CustomerAuthController::class, 'login'])->name('customer.login.store');
    Route::get('/customer/register', [CustomerAuthController::class, 'showRegister'])->name('customer.register');
    Route::post('/customer/register', [CustomerAuthController::class, 'register'])->name('customer.register.store');
});

Route::middleware('auth:customer')->group(function (): void {
    Route::post('/customer/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');
    Route::post('/catalog/{vehicle}/book', [CustomerRentalController::class, 'store'])->name('customer.rentals.store');
    Route::get('/my-rentals', [CustomerRentalController::class, 'index'])->name('customer.rentals.index');
    Route::get('/my-rentals/{rental}', [CustomerRentalController::class, 'show'])->name('customer.rentals.show');
    Route::post('/my-rentals/{rental}/cancel', [CustomerRentalController::class, 'cancel'])->name('customer.rentals.cancel');
    Route::get('/my-profile', [CustomerProfileController::class, 'edit'])->name('customer.profile.edit');
    Route::put('/my-profile', [CustomerProfileController::class, 'update'])->name('customer.profile.update');
});
