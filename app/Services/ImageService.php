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
use Intervention\Image\Image;
use Intervention\Image\ImageManagerStatic;

class ImageService
{
    protected S3Client $client;
    public array $images;
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
            $this->images[] = [
                "collection" => $this->collection,
                "file_name" => Str::random(40) . '.' . $this->extension,
                "data" => $this->compress(
                    ImageManagerStatic::make($file->getPathname())
                        ->orientate()
                )
            ];
        }

        foreach ($this->images as $i => $image) {
            $this->images[$i]['mime_type'] = $image['data']->mime();
        }
    }

    /**
     * @throws Exception
     */
    public function store()
    {
        $this->rekognize();

        $pool = $this->getCommandPool($this->getCommands());

        try {
            $pool->promise()->wait();
        } catch (Exception $e) {
            $this->rollback();
            throw new Exception($e->getMessage());
        }
    }

    public function compress($image): Image
    {
        if ($image->width() < $image->height()) {
            if ($image->width() < $this->defaultSize) {
                $widthResize = $image->width();
            } else {
                $widthResize = $this->defaultSize;
            }
        } else {
            if ($image->height() < $this->defaultSize) {
                $heightResize = $image->height();
            } else {
                $heightResize = $this->defaultSize;
            }
        }

        return $image->resize(
            $widthResize ?? null,
            $heightResize ?? null,
            function ($constraint) {
                $constraint->aspectRatio();
            }
        )->save(null, 75, $this->extension);
    }

    public function rollback()
    {
        foreach ($this->images as $image) {
            Storage::delete($image['collection'] . '/' . $image['file_name']);
        }
    }

    public function getCommands(): array
    {
        $commands = [];

        foreach ($this->images as $image) {
            $commands[] = $this->client->getCommand('PutObject', [
                'Bucket' => config('filesystems.disks.s3.bucket'),
                'Key' => $image['collection'] . '/' . $image['file_name'],
                'Body' => $image['data'],
                'ContentType' => $image['mime_type']
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
        $imageBinaries = array_map(function ($image) {
            return $image['data'];
        }, $this->images);

        $rekognition = new RekognitionService($imageBinaries);
        if ($rekognize = $rekognition->rekognize()) {
            if ($rekognize == 'Connection Error') {
                throw new Exception(__('misc.unknown_error'));
            } else {
                throw new Exception(__('misc.rekognition_failure', [
                    'reason' => __($rekognize['ParentName'])
                ]));
            }
        }
    }

}
