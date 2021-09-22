<?php

namespace App\Services;

use App\Models\Media;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Image;
use Intervention\Image\ImageManagerStatic;

class ImageService
{
    protected int $defaultSize = 1080;
    protected string $extension = 'jpg';

    public function __construct(protected Media $image)
    {
        //
    }

    /**
     * @throws Exception
     */
    public function store()
    {
        try {
            $compressed = $this->compress(ImageManagerStatic::make($this->image->getTempFile()))
                ->orientate();
        } catch (FileNotFoundException) {
            $this->rollback();
            return;
        }

        $this->rekognize();

        $fileName = $this->image->collection . '/' . $this->image->id . '.' . $this->extension;

        Storage::put($fileName, $compressed);

        $this->image->file_name = $fileName;
        $this->image->mime_type = $compressed->mime();
        $this->image->save();

        Storage::disk('local')->delete($this->image->temp_file_name);
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
        )->save($this->image->getTempFilePath(), 75, $this->extension);
    }

    public function rollback()
    {
        Storage::delete($this->image->file_name);
        Storage::disk('local')->delete($this->image->temp_file_name);
    }

    /**
     * @throws Exception
     */
    public function rekognize(): void
    {
        $rekognition = new RekognitionService($this->image);
        if ($rekognize = $rekognition->rekognize()) {
            $this->rollback();
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
