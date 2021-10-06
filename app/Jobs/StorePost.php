<?php

namespace App\Jobs;

use App\Models\Post;
use App\Services\ImageService;
use App\Services\VideoService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class StorePost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 300;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected Post $post)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $published = true;

        foreach ($this->post->images as $image) {
            $imageService = new ImageService($image);
            try {
                $imageService->store();
            } catch (Exception $e) {
                Log::channel('info')->info($e->getMessage(), ['media_id' => $image->id]);
                // todo: send notification to user here.
                return;
            }

            $image->processed = true;
            $image->approved = true;
            $image->save();

            $published = true;
        }

        foreach ($this->post->videos as $video) {
            $videoService = new VideoService($video);
            try {
                $videoService->store();
            } catch (Exception $e) {
                Log::channel('info')->info($e->getMessage(), ['media_id' => $video->id]);
                // todo: send notification to user here.
                return;
            }
            $video->processed = true;
            $video->approved = false; // will be approved manually by an admin
            $video->save();

            $published = false;
        }

        $this->post->published = $published;
        $this->post->save();
    }
}
