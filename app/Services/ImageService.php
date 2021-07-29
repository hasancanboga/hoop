<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Image;
use Intervention\Image\ImageManagerStatic;

class ImageService
{
    protected Image $image;
    protected int $w;
    protected int $h;
    protected int $defaultSize = 1080;
    protected string $extension = 'png';

    public function __construct(protected UploadedFile $file)
    {
        $this->image = ImageManagerStatic::make($this->file->getPathname());
        $this->image->orientate();
        $this->w = $this->image->width();
        $this->h = $this->image->height();
    }

    /**
     * @param string $path
     * @return string
     */
    public function store(string $path): string
    {
        return Storage::put(
            $this->generateName($path),
            $this->compress()
        );
    }

    public function compress(): Image
    {
        if ($this->w < $this->h) {
            if ($this->w < $this->defaultSize) {
                $widthResize = $this->w;
            } else {
                $widthResize = $this->defaultSize;
            }
        } else {
            if ($this->h < $this->defaultSize) {
                $heightResize = $this->h;
            } else {
                $heightResize = $this->defaultSize;
            }
        }

        return $this->image->resize(
            $widthResize ?? null,
            $heightResize ?? null,
            function ($constraint) {
                $constraint->aspectRatio();
            }
        )->save(null, 75, 'jpg');
    }

    public function generateName($path): string
    {
        return $path . '/' . Str::random(40) . '.' . $this->extension;
    }

}
