<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{

    public function self()
    {
        return request()->user();
    }

    public function show(User $user)
    {
        return $user;
    }

    public function update(Request $request, $id)
    {
        //
    }


}
