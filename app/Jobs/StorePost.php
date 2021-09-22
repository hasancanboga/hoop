<?php

namespace App\Jobs;

use App\Models\Post;
use App\Services\ImageService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class StorePost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        foreach ($this->post->images as $image) {
            $imageService = new ImageService($image);
            try {
                $imageService->store();
            } catch (Exception $e) {
                Log::channel('info')->info($e->getMessage(), ['image' => $image]);
                // todo: send notification to user here.
            }
        }
    }
}
