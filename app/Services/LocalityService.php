<?php

namespace App\Services;

use App\Models\Locality;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Model;
use MenaraSolutions\Geographer\City;
use MenaraSolutions\Geographer\Country;
use MenaraSolutions\Geographer\State;

class LocalityService
{

    public function __construct(protected int $code)
    {
        //
    }

    public function store(User $user): Model|bool|Locality
    {
        if (!$locality = $this->buildLocality()) {
            return false;
        }

        if ($locality instanceof State) {
            $countryCode = $locality->parent()->code;
            $stateCode = $locality->code ?? null;
            $cityCode = null;
        } else {
            $countryCode = $locality->parent()->parent()->code ?? null;
            $stateCode = $locality->parent()->code ?? null;
            $cityCode = $locality->code ?? null;
        }

        return Locality::create([
            'user_id' => $user->id,
            'country_code' => $countryCode,
            'state_code' => $stateCode,
            'city_code' => $cityCode,
        ]);
    }

    public function buildLocality(): Country|bool|City|State
    {
        try {
            return State::build($this->code);
        } catch (Exception) {
            try {
                return City::build($this->code);
            } catch (Exception) {
                return false;
            }
        }
    }
}
