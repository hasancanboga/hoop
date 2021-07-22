<?php

use App\Http\Controllers\Api\ExploreController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\TimelineController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\FollowController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\ConfirmOtpController;
use App\Http\Controllers\Api\Auth\RegisterParentController;
use App\Http\Controllers\Api\Auth\CompleteRegistrationController;

Route::post('/login', [LoginController::class, 'store']);
Route::post('/confirm-otp', [ConfirmOtpController::class, 'store']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [UserController::class, 'self']);
    Route::post('/logout', [LoginController::class, 'destroy']);

    Route::post('/complete-registration', [CompleteRegistrationController::class, 'store']);
    Route::post('/register-parent', [RegisterParentController::class, 'store'])
        ->middleware('register.completed:api');
});

Route::middleware([
    'auth:sanctum',
    'register.completed:api',
    'register.parent:api'
])->group(function () {
    Route::post('/user/update', [UserController::class, 'update']);

    Route::get('/users/{user:username}', [UserController::class, 'show']);

    Route::post('/users/{user:username}/follow', [FollowController::class, 'store']);
    Route::post('/users/{user:username}/unfollow', [FollowController::class, 'destroy']);

    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/timeline', [TimelineController::class, 'index']);
    Route::get('/explore', [ExploreController::class, 'index']);
    Route::get('/posts/{post}', [PostController::class, 'show']);
    Route::post('/posts', [PostController::class, 'store']);
});
