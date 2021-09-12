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
        'path'
    ];

    public function getPathAttribute(): ?string
    {
        $path = $this->collection . '/' . $this->file_name;
        return Storage::url($path);
    }
}
