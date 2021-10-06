<?php

namespace App\Models;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin IdeHelperMedia
 */
class Media extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = [
        'url'
    ];

    public function getPathAttribute(): ?string
    {
        return $this->collection . '/' . $this->file_name;
    }

    /** @noinspection PhpUnused */
    public function getUrlAttribute(): ?string
    {
        return Storage::url($this->path);
    }

    public function getTempFilePath(): string
    {
        return storage_path('app/' . $this->temp_file_name);
    }

    /**
     * @throws FileNotFoundException
     */
    public function getTempFile(): string
    {
        return File::get($this->getTempFilePath());
    }

    public function getTempFileExtension(): string
    {
        $exploded = explode('.', $this->temp_file_name);
        return $exploded[count($exploded) - 1];
    }

    public function mediable(): MorphTo
    {
        return $this->morphTo('model');
    }

    function process()
    {
        $this->processed = true;
        $this->save();
    }

    function approve()
    {
        $this->approved = true;
        $this->save();
    }

    function processAndApprove()
    {
        $this->processed = true;
        $this->approved = true;
        $this->save();
    }
}
