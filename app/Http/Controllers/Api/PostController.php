<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Jobs\StorePost;
use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class PostController extends Controller
{
    public function show(int $id): Model|Collection|Builder|array|null
    {
        return Post::with('user', 'images', 'videos')->find($id);
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function index(User $user): LengthAwarePaginator
    {
        return $user->posts()->published()->with(['images', 'videos'])->latest()->paginate(10);
    }

    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();

        $postImages = [];
        $postVideos = [];

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
            'body' => $validated['body'],
        ]);

        $post->images()->createMany($postImages);
        $post->videos()->createMany($postVideos);
        dispatch(new StorePost($post));
    }

    /*    public function store2(StorePostRequest $request): Response|Application|ResponseFactory
        {
            $validated = $request->validated();

            $postImages = [];
            $postVideos = [];

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

            if (request('videos')) {
                $videoService = new VideoService(
                    $request->file('videos'),
                    'post_videos'
                );

                try {
                    $videoService->store();
                } catch (Exception $e) {
                    return response(message($e->getMessage()), 400);
                }

                foreach ($videoService->videos as $video) {
                    $postImages[] = [
                        'collection' => $video['collection'],
                        'file_name' => $video['file_name'],
                        'type' => 'video',
                        'mime_type' => $video['mime_type'],
                    ];
                }
            }


            $post = Post::create([
                'user_id' => auth()->id(),
                'body' => $validated['body'],
            ]);

            $post->images()->createMany($postImages);
            $post->videos()->createMany($postVideos);

            return response($post->load(['user', 'images', 'videos']), 200);
        }*/

    public function delete(Post $post)
    {
        $post->delete();
    }
}
