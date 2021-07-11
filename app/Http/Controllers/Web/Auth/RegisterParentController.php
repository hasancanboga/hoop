<?php

namespace App\Http\Controllers\Web\Auth;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;

class RegisterParentController extends Controller
{
    public function create()
    {
        if (auth()->user()->age >= 13 || auth()->user()->hasRegisteredParent()) {
            return redirect(RouteServiceProvider::HOME);
        }
        return Inertia::render('Auth/RegisterParent',);
    }
}
