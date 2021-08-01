<?php

namespace App\Services;

use Aws\CommandInterface;
use Aws\CommandPool;
use Aws\Exception\AwsException;
use Aws\ResultInterface;
use Aws\S3\S3Client;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\Image;
use Intervention\Image\ImageManagerStatic;

class ImageService
{
    protected array $images;
    protected bool $hasError = false;
    protected int $defaultSize = 1080;
    protected string $extension = 'jpg';

    public function __construct(protected array|UploadedFile $files, protected string $path)
    {
        if (!is_array($files)) {
            $files = [$files];
        }

        foreach ($files as $file) {
            $this->images[$this->generateName()] = ImageManagerStatic::make($file->getPathname())
                ->orientate();
        }
    }

    /**
     * @return array|string
     * @throws Exception
     */
    public function store(): array|string
    {
        // Laravel S3 Driver mode (single file upload at a time):
        // Storage::put($name, $this->compress());
        // return $name;

        // Native AWS mode with Guzzle promises (Multiple file uploads):

        $s3 = new S3Client(config('aws'));

        $commands = $this->getCommands($s3);

        $pool = $this->getCommandPool($s3, $commands);

        try {
            $pool->promise()->wait();
        } catch (Exception) {
            $this->hasError = true;
        }

        if ($this->hasError) {
            $this->deleteImage();
            throw new Exception;
        }

        return count($this->images) == 1
            ? array_key_first($this->images)
            : array_keys($this->images);
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

    public function generateName(): string
    {
        return $this->path . '/' . Str::random(40) . '.' . $this->extension;
    }

    public function deleteImage()
    {

    }

    public function rekognize()
    {
        // MultipartUploader
        // $rekognition = new RekognitionClient(config('aws'));
        // enable support for multiple file uploads before this.
    }

    /**
     * @param S3Client $s3
     * @return array
     */
    public function getCommands(S3Client $s3): array
    {
        $commands = [];

        foreach ($this->images as $name => $image) {
            $image = $this->compress($image);

            $commands[] = $s3->getCommand('PutObject', [
                'Bucket' => config('filesystems.disks.s3.bucket'),
                'Key' => $name,
                'Body' => $image,
                'ContentType' => $image->mime()
            ]);
        }

        return $commands;
    }

    /**
     * @param S3Client $s3
     * @param array $commands
     * @return CommandPool
     */
    public function getCommandPool(S3Client $s3, array $commands): CommandPool
    {
        return new CommandPool($s3, $commands, [
            'concurrency' => 5,
            'before' => function (CommandInterface $cmd) {
                // gc_collect_cycles();
            },
            'fulfilled' => function (ResultInterface $result) {
                //
            },
            'rejected' => function (AwsException $reason) {
                throw new Exception($reason);
            },
        ]);
    }

}
