<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ConfirmOtpRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ConfirmOtpController extends Controller
{
    /**
     * Confirm the user's password.
     *
     * @param ConfirmOtpRequest $request
     * @return Application|ResponseFactory|Response
     * @throws ValidationException
     */
    public function store(ConfirmOtpRequest $request): Response|Application|ResponseFactory
    {
        $request->authenticate();

        return response(null, 201)->withHeaders([
            'Auth' => $request->user()->createToken($request->phone)->plainTextToken
        ]);
    }
}
