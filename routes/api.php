<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\ConfirmOtpController;


Route::post('/login', [LoginController::class, 'store']);
Route::post('/confirm-otp', [ConfirmOtpController::class, 'store']);

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });