<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Rules\ValidImageAspectRatio;
use App\Services\ImageService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostController extends Controller
{
    public function show(int $id): Model|Collection|Builder|array|null
    {
        return Post::with('user')->find($id);
    }

    public function index(User $user): LengthAwarePaginator
    {
        return $user->posts()->latest()->paginate(10);
    }

    public function store(Request $request): Response|Application|ResponseFactory
    {
        $validated = $request->validate([
            'body' => ['required', 'max:255'],
            'images' => ['array', 'max:1'],
            'images.*' => ['image', 'max:5000', new ValidImageAspectRatio],
        ]);

        $postImages = [];

        if (request('images')) {
            $imageService = new ImageService(
                $request->file('images'),
                'post_images'
            );

            try {
                $imageService->store();
            } catch (Exception $e) {
                return response(message($e->getMessage()), 400);
            }

            foreach ($imageService->images as $image) {
                $postImages[] = [
                    'collection' => $image['collection'],
                    'file_name' => $image['file_name'],
                    'type' => 'image',
                    'mime_type' => $image['mime_type'],
                ];
            }
        }

        $post = Post::create([
            'user_id' => auth()->id(),
            'body' => $validated['body'],
        ]);

        $post->images()->createMany($postImages);
        // $post->videos()->createMany($postVideos);

        return response($post->load(['user', 'images']), 200);
    }

    public function delete(Post $post)
    {
        $post->delete();
    }
}
