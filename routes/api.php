<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\ConfirmOtpController;
use App\Http\Controllers\Api\Auth\CompleteRegistrationController;
use App\Http\Controllers\Api\PostController;

Route::post('/login', [LoginController::class, 'store']);
Route::post('/confirm-otp', [ConfirmOtpController::class, 'store']);


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', fn () => request()->user());

    Route::post('/logout', [LoginController::class, 'destroy']);
    Route::post('/complete-registration', [CompleteRegistrationController::class, 'store']);
    
    Route::get('/timeline', [PostController::class, 'timeline']);


});
