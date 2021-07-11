<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ConfirmOtpRequest;

class ConfirmOtpController extends Controller
{
    /**
     * Confirm the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function store(ConfirmOtpRequest $request)
    {
        $request->authenticate();
        
        return response(null, 201)->withHeaders([
            'Auth' => $request->user()->createToken($request->phone)->plainTextToken
        ]);
    }
}
