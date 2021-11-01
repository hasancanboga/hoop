<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Jobs\StorePost;
use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;

class PostController extends Controller
{
    public function show(int $id): Model|Collection|Response|Builder|array|Application|ResponseFactory
    {
        $post = Post::with('user', 'images', 'videos')
            ->withCount(['likes', 'comments'])
            ->find($id);

        if (!$post) {
            return response(message(__('Post Not Found')), 404);
        }
        return $post;
    }

    public function comments(int $id): Response|LengthAwarePaginator|Application|ResponseFactory
    {
        if (!$post = Post::find($id)) {
            return response(message(__('Post Not Found')), 404);
        }

        return $post->comments()->paginate(1);
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function index(User $user): LengthAwarePaginator
    {
        return $user
            ->posts()
            ->published()
            ->with(['images', 'videos'])
            ->withCount(['likes', 'comments'])
            ->latest()
            ->paginate(10);
    }

    public function store(StorePostRequest $request): Response|Application|ResponseFactory
    {
        $validated = $request->validated();

        $postImages = [];
        $postVideos = [];

        if (request('parent_id') && (request('images') || request('videos'))) {
            return response(message(__('Media is not allowed in comments.')), 400);
        }

        if (request('images')) {
            foreach (request('images') as $image) {
                $postImages[] = [
                    'collection' => 'post_images',
                    'type' => 'image',
                    'temp_file_name' => $image->store('temp/post-images', 'local')
                ];
            }
        }

        if (request('videos')) {
            foreach (request('videos') as $video) {
                $postImages[] = [
                    'collection' => 'post_videos',
                    'type' => 'video',
                    'temp_file_name' => $video->store('temp/post-videos', 'local')
                ];
            }
        }

        $post = Post::create([
            'user_id' => auth()->id(),
            'parent_id' => request('parent_id'),
            'body' => $validated['body'],
        ]);

        $post->images()->createMany($postImages);
        $post->videos()->createMany($postVideos);

        if (request('parent_id')) {
            dispatch_sync(new StorePost($post));
        } else {
            dispatch(new StorePost($post));
        }
        return response(null);
    }

    public function delete(Post $post)
    {
        $post->delete();
    }
}
