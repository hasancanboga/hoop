<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ConfirmOtpRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class ConfirmOtpController extends Controller
{
    /**
     * Show the confirm password view.
     * @return Response
     */
    public function show(): Response
    {
        return Inertia::render('Auth/ConfirmOtp', [
            'temp_user' => session('temp_user'),
        ]);
    }

    /**
     * Confirm the user's password.
     *
     * @param ConfirmOtpRequest $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(ConfirmOtpRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();
        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
