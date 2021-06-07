<?php

namespace App\Http\Controllers\Web\Auth;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\Auth\ConfirmOtpRequest;
use Illuminate\Validation\ValidationException;

class ConfirmOtpController extends Controller
{
    /**
     * Show the confirm password view.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return Inertia::render('Auth/ConfirmOtp', [
            'temp_user' => session('temp_user'), 
        ]);
    }

    /**
     * Confirm the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function store(ConfirmOtpRequest $request)
    {   
        $request->authenticate();
        $request->session()->regenerate();
        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
