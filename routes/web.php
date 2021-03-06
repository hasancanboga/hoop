<?php

use App\Http\Controllers\Web\WelcomeController;
use App\Http\Controllers\Web\LanguageController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\Auth\LoginController;
use App\Http\Controllers\Web\Auth\ConfirmOtpController;
use App\Http\Controllers\Web\Auth\RegisterParentController;
use App\Http\Controllers\Web\Auth\CompleteRegistrationController;

Route::get('language/{language}', [LanguageController::class, 'set'])->name('language');


Route::middleware(['guest'])->group(function () {
    Route::get('/', [WelcomeController::class, 'index']);

    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    Route::get('/confirm-otp', [ConfirmOtpController::class, 'show'])->name('otp');
    Route::post('/confirm-otp', [ConfirmOtpController::class, 'store'])->name('otp.confirm');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/complete-registration', [CompleteRegistrationController::class, 'create'])
        ->name('register.complete');
    Route::post('/complete-registration', [CompleteRegistrationController::class, 'store']);
    Route::get('/register-parent', [RegisterParentController::class, 'create'])
        ->middleware('register.completed:web')->name('register.parent');
});

Route::middleware([
    'auth',
    'register.completed:web',
    'register.parent:web'
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

if (app()->environment('local')) {
    require __DIR__ . '/test.php';
}
