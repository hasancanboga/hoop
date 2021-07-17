<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FollowController extends Controller
{
    public function store(Request $request, User $user)
    {
        $request->user()->follow($user);
    }

    public function destroy(Request $request, User $user)
    {
        $request->user()->unfollow($user);
    }
}
