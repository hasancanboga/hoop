<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\ConfirmOtpController;
use App\Http\Controllers\Api\Auth\CompleteRegistrationController;

Route::post('/login', [LoginController::class, 'store']);
Route::post('/confirm-otp', [ConfirmOtpController::class, 'store']);


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy']);
    Route::post('/complete-registration', [CompleteRegistrationController::class, 'store']);
    
    Route::get('/user', fn () => request()->user());
});
