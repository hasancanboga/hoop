<?php

namespace App\Models;

use MenaraSolutions\Geographer\City;
use MenaraSolutions\Geographer\State;
use Illuminate\Database\Eloquent\Model;
use MenaraSolutions\Geographer\Country;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Locality extends Model
{
    use HasFactory;


    protected $guarded = [];
    protected $name = "";

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCity()
    {
        return $this->city_code ? City::build($this->city_code) : null;
    }

    public function getState()
    {
        return $this->state_code ? State::build($this->state_code) : null;
    }

    public function getCountry()
    {
        return $this->country_code ? Country::build($this->country_code) : null;
    }

    public function getName()
    {
        $fields = [];

        if ($this->city_code) {
            $fields[] = $this->getCity()->name;
        }

        if ($this->state_code) {
            $fields[] = $this->getState()->name;
        }

        // if ($this->country_code) {
        //     $fields[] = $this->getCountry()->code;
        // }

        return implode(', ', $fields);
    }
}
