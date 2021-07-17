<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class WelcomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }
}
