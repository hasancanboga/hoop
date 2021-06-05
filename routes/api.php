<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;


Route::post('/phone', function (Request $request) {
    dump(phone($request->phone, $request->phone_country)->formatE164());
});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });