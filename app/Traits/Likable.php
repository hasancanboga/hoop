<?php


namespace App\Traits;


use App\Models\Like;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Response;

trait Likable
{
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function like(): Response|Application|ResponseFactory
    {
        if ($this->isLikedBy(request()->user())) {
            return response(null);
        }

        $this->likes()->create([
            'user_id' => auth()->id(),
        ]);

        /** @noinspection PhpStatementHasEmptyBodyInspection */
        if (!$this->likes()->onlyTrashed()->where('user_id', auth()->id())->count()) {
            // todo: send notification
        }

        return response(null);
    }

    public function unlike(): Response|Application|ResponseFactory
    {
        // todo: clear soft deleted items periodically. (once a week?)
        request()->user()->likes()->where('post_id', $this->id)->delete();
        return response(null);
    }

    public function isLikedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $this->id)->exists();
    }
}
