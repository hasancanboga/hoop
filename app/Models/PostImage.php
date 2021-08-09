<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin IdeHelperPostImage
 */
class PostImage extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getPathAttribute($value): ?string
    {
        return $value ? Storage::url($value) : null;
    }
}
