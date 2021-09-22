<?php

namespace App\Models;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

}
