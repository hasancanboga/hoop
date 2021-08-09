<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperStunt
 */
class Stunt extends Model
{
    use HasFactory;

    public function challenge(): BelongsTo
    {
        return $this->belongsTo(Challenge::class);
    }
}
