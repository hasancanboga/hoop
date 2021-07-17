<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait Followable
{
    public function follow(User $user): Model|null
    {
        if (!$this->isFollowing($user)) {
            return $this->follows()->save($user);
        }
        return null;
    }

    /** @noinspection PhpUnused */
    public function unfollow(User $user): int
    {
        return $this->follows()->detach($user);
    }

    public function follows(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'user_id', 'following_user_id')->withTimestamps();
    }

    public function isFollowing(User $user): bool
    {
        return $this->follows()->where('following_user_id', $user->id)->exists();
    }
}
