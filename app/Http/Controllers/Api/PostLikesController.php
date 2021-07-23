<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class PostLikesController extends Controller
{
    public function store(Post $post): Response|Application|ResponseFactory
    {
        return $post->like();
    }

    public function destroy(Post $post): Response|Application|ResponseFactory
    {
        return $post->unlike();
    }
}
