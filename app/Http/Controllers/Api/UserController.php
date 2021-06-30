<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{

    public function show($id)
    {
        //    
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function timeline()
    {
        return Post::with('user')->where('user_id', auth()->user()->id)->latest()->get();
    }
}
