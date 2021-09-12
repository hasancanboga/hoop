<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    public function getUrlAttribute(): ?string
    {
        return Storage::url($this->path);
    }
}
