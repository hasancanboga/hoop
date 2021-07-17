<?php

namespace App\Rules;

use App\Services\LocalityService;
use Illuminate\Contracts\Validation\Rule;
use MenaraSolutions\Geographer\City;
use MenaraSolutions\Geographer\Country;
use MenaraSolutions\Geographer\State;

class GeonamesCodeExists implements Rule
{

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     */
    public function passes($attribute, $value): bool|Country|City|State
    {
        $localityService = new LocalityService($value);
        return $localityService->buildLocality();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return __('misc.geocode_not_found');
    }
}
