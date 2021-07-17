<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function store(LoginRequest $request)
    {
        $request->loginOrRegister();
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function destroy()
    {
        auth()->user()->tokens()->delete();
    }
}
