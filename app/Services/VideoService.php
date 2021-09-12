<?php

namespace App\Services;

use Aws\CommandPool;
use Aws\Exception\AwsException;
use Aws\ResultInterface;
use Aws\S3\S3Client;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoService
{
    protected S3Client $client;
    public array $videos;
    protected bool $hasError = false;
    protected int $defaultSize = 1080;
    protected string $extension = 'jpg';

    public function __construct(protected array|UploadedFile $files, protected string $collection)
    {
        $this->client = new S3Client(config('aws'));

        if (!is_array($files)) {
            $this->files = [$files];
        }

        foreach ($this->files as $file) {
            $this->videos[] = [
                "collection" => $this->collection,
                "file_name" => Str::random(40) . '.' . $file->getClientOriginalExtension(),
                "data" => $file->getContent(),
                "mime_type" => $file->getMimeType(),
            ];
        }
    }

    /**
     * @throws Exception
     */
    public function store()
    {
        // $this->rekognize();

        $pool = $this->getCommandPool($this->getCommands());

        try {
            $pool->promise()->wait();
        } catch (Exception $e) {
            $this->rollback();
            throw new Exception($e->getMessage());
        }
    }

    public function rollback()
    {
        foreach ($this->videos as $video) {
            Storage::delete($video['collection'] . '/' . $video['file_name']);
        }
    }

    public function getCommands(): array
    {
        $commands = [];

        foreach ($this->videos as $video) {

            $commands[] = $this->client->getCommand('PutObject', [
                'Bucket' => config('filesystems.disks.s3.bucket'),
                'Key' => $video['collection'] . '/' . $video['file_name'],
                'Body' => $video['data'],
                'ContentType' => $video['mime_type']
            ]);
        }

        return $commands;
    }

    /**
     * @param array $commands
     * @return CommandPool
     */
    public function getCommandPool(array $commands): CommandPool
    {
        return new CommandPool($this->client, $commands, [
            'concurrency' => 5,
            'before' => function () {
                gc_collect_cycles();
            },
            'fulfilled' => function (ResultInterface $result) {
                //
            },
            'rejected' => function (AwsException $reason) {
                throw new Exception($reason);
            },
        ]);
    }

    /**
     * @throws Exception
     */
    public function rekognize(): void
    {
        // $imageBinaries = array_map(function ($image) {
        //     return $image['data'];
        // }, $this->images);
        //
        // $rekognition = new RekognitionService($imageBinaries);
        // if ($rekognize = $rekognition->rekognize()) {
        //     if ($rekognize == 'Connection Error') {
        //         throw new Exception(__('misc.unknown_error'));
        //     } else {
        //         throw new Exception(__('misc.rekognition_failure', [
        //             'reason' => __($rekognize['ParentName'])
        //         ]));
        //     }
        // }
    }

}
