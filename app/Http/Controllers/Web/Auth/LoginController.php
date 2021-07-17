<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class LoginController extends Controller
{
    /**
     * Display the login view.
     *
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param LoginRequest $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->loginOrRegister();
        return redirect()->route('otp.confirm')->with(['temp_user' => $request->user()]);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
