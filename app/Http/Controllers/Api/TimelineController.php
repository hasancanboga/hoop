<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TimelineController extends Controller
{
    public function __invoke(Request $request)
    {
        return $request->user()->timeline(true);
    }
}
