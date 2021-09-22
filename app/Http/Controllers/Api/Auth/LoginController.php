<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function store(LoginRequest $request): Response|Application|ResponseFactory
    {
        return $request->loginOrRegister();
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function destroy()
    {
        auth()->user()->tokens()->delete();
    }
}
