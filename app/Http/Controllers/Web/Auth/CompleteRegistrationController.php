<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CompleteRegistrationRequest;
use App\Providers\RouteServiceProvider;
use App\Services\UserService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Inertia\Inertia;
use Inertia\Response;
use MenaraSolutions\Geographer\Country;

class CompleteRegistrationController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display the registration view.
     *
     * @param Request $request
     * @return Response|Redirector|RedirectResponse|Application
     */
    public function create(Request $request): Response|Redirector|RedirectResponse|Application
    {
        if ($request->user()->hasCompletedRegistration()) {
            return redirect(RouteServiceProvider::HOME);
        }

        return Inertia::render('Auth/CompleteRegistration', [
            'localities' => Country::build('TR')
                ->getStates()->sortBy('isoCode')->toArray()
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @param CompleteRegistrationRequest $request
     * @return RedirectResponse
     */
    public function store(CompleteRegistrationRequest $request): RedirectResponse
    {
        if ($request->user()->hasCompletedRegistration()) {
            return redirect(RouteServiceProvider::HOME);
        }

        $this->userService->completeRegistration($request);
        return redirect(RouteServiceProvider::HOME);
    }
}
