<?php

use App\Http\Controllers\FollowsController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\ConfirmOtpController;
use App\Http\Controllers\Api\Auth\CompleteRegistrationController;

Route::post('/login', [LoginController::class, 'store']);
Route::post('/confirm-otp', [ConfirmOtpController::class, 'store']);


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [UserController::class, 'self']);
    Route::get('/users/{user}', [UserController::class, 'show']);

    Route::post('/users/{user}/follow', [FollowsController::class, 'store']);
    Route::post('/users/{user}/unfollow', [FollowsController::class, 'destroy']);

    Route::post('/logout', [LoginController::class, 'destroy']);
    Route::post('/complete-registration', [CompleteRegistrationController::class, 'store']);

    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/timeline', [PostController::class, 'timeline']);
    Route::post('/posts', [PostController::class, 'store']);
});
