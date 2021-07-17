<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MenaraSolutions\Geographer\City;
use MenaraSolutions\Geographer\Country;
use MenaraSolutions\Geographer\State;

/**
 * @mixin IdeHelperLocality
 */
class Locality extends Model
{
    use HasFactory;


    protected $guarded = [];
    protected string $name = "";

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getCity(): City|null
    {
        return $this->city_code ? City::build($this->city_code) : null;
    }

    public function getState(): State|null
    {
        return $this->state_code ? State::build($this->state_code) : null;
    }

    /** @noinspection PhpUnused */
    public function getCountry(): Country|null
    {
        return $this->country_code ? Country::build($this->country_code) : null;
    }

    public function getName(): string
    {
        $fields = [];

        if ($this->city_code) {
            if (isset($this->getCity()->name)) {
                $fields[] = $this->getCity()->name;
            }
        }

        if ($this->state_code) {
            if (isset($this->getState()->name)) {
                $fields[] = $this->getState()->name;
            }
        }

        // if ($this->country_code) {
        //     $fields[] = $this->getCountry()->code;
        // }

        return implode(', ', $fields);
    }
}
