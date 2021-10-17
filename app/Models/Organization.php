<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperOrganization
 */
class Organization extends Model
{
    use HasFactory;

    protected $guarded = [];
}
