<?php

namespace App\Models;

use App\Traits\Likable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin IdeHelperPost
 */
class Post extends Model
{
    use Likable, HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::deleting(function ($post) {
            if ($post->images->isNotEmpty()) {
                $post->deleteImages();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(PostImage::class);
    }

    public function deleteImages()
    {
        if ($this->images) {
            // getRawOriginal() used in order to skip eloquent accessor (turns into URL)
            foreach($this->images as $image){
                Storage::delete($image->getRawOriginal('path'));
            }
        }
    }
}
