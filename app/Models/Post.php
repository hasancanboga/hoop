<?php

namespace App\Models;

use App\Traits\Likable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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
            if ($post->videos->isNotEmpty()) {
                $post->deleteVideos();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Media::class, 'model')->where('type', 'image');
    }

    public function videos(): MorphMany
    {
        return $this->morphMany(Media::class, 'model')->where('type', 'video');
    }

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    public function deleteImages()
    {
        if ($this->images) {
            foreach ($this->images as $image) {
                Storage::delete($image->path);
                $image->delete();
            }
        }
    }

    public function deleteVideos()
    {
        if ($this->videos) {
            foreach ($this->videos as $video) {
                Storage::delete($video->path);
                $video->delete();
            }
        }
    }
}
