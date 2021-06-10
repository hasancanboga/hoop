<?php

use App\Http\Controllers\Web\WelcomeController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\Auth\LoginController;
use App\Http\Controllers\Web\Auth\ConfirmOtpController;
use App\Http\Controllers\Web\Auth\CompleteRegistrationController;


Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/complete-registration', [CompleteRegistrationController::class, 'create'])
        ->name('register.complete');
    Route::post('/complete-registration', [CompleteRegistrationController::class, 'store']);
});

Route::middleware(['auth', 'register.completed'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/', [WelcomeController::class, 'index']);

    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    Route::get('/confirm-otp', [ConfirmOtpController::class, 'show'])->name('otp');
    Route::post('/confirm-otp', [ConfirmOtpController::class, 'store'])->name('otp.confirm');
});

if (app()->environment('local')) {
    require __DIR__ . '/test.php';
}
