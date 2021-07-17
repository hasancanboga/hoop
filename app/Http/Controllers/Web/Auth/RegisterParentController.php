<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Inertia\Inertia;
use Inertia\Response;

class RegisterParentController extends Controller
{
    /**
     * @param Request $request
     * @return Response|Redirector|RedirectResponse|Application
     */
    public function create(Request $request): Response|Redirector|RedirectResponse|Application
    {
        if ($request->user()->age >= 13 || $request->user()->hasRegisteredParent()) {
            return redirect(RouteServiceProvider::HOME);
        }
        return Inertia::render('Auth/RegisterParent');
    }
}
