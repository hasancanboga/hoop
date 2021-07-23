<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function show(int $id): Model|Collection|Builder|array|null
    {
        return Post::with('user')->find($id);
    }

    public function index(Request $request)
    {
        return $request->user()->posts()->latest()->paginate(10);
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
