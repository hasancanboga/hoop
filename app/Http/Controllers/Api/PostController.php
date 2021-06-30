<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends Controller
{

    public function index()
    {
        return auth()->user()->timeline(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'body' => 'required|max:255'
        ]);

        Post::create([
            'user_id' => auth()->id(),
            'body' => $validated['body'],
        ]);
    }
}
