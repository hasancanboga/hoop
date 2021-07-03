<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FollowController extends Controller
{
    public function store(User $user)
    {
        auth()->user()->follow($user);
    }
    
    public function destroy(User $user)
    {
        auth()->user()->unfollow($user);
    }
}
