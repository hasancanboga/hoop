<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('Dashboard');
    }
}
