<?php

namespace App\Services;

use Exception;
use App\Models\User;
use App\Models\Locality;
use MenaraSolutions\Geographer\City;
use MenaraSolutions\Geographer\State;

class LocalityService
{
    protected $code;

    public function __construct(int $code)
    {
        $this->code = $code;
    }

    public function store(User $user)
    {
        if (!$locality = $this->buildLocality()) {
            return false;
        }

        if ($locality instanceof State) {
            $countryCode = $locality->parent()->code;
            $stateCode = $locality->code;
            $cityCode = null;
        } else {
            $countryCode = $locality->parent()->parent()->code;
            $stateCode = $locality->parent()->code;
            $cityCode =  $locality->code;
        }

        return Locality::create([
            'user_id' => $user->id,
            'country_code' => $countryCode,
            'state_code' => $stateCode,
            'city_code' => $cityCode,
        ]);
    }

    public function buildLocality()
    {
        try {
            return State::build($this->code);
        } catch (Exception $e) {
            try {
                return City::build($this->code);
            } catch (Exception $e) {
                return false;
            }
        }
    }
}
