<?php

namespace App\Http\Controllers\Web\Auth;

use Inertia\Inertia;
use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\Auth\CompleteRegistrationRequest;

class CompleteRegistrationController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if (auth()->user()->hasCompletedRegistration()) {
            return redirect(RouteServiceProvider::HOME);
        }
        return Inertia::render('Auth/CompleteRegistration');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(CompleteRegistrationRequest $request)
    {
        if ($request->user()->hasCompletedRegistration()) {
            return redirect(RouteServiceProvider::HOME);
        }
        $this->userService->completeRegistration($request);
        return redirect(RouteServiceProvider::HOME);
    }
}
