<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperChallenge
 */
class Challenge extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function stunts(): HasMany
    {
        return $this->hasMany(Stunt::class);
    }
}
