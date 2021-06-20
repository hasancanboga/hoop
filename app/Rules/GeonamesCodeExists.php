<?php

namespace App\Rules;

use App\Services\LocalityService;
use Illuminate\Contracts\Validation\Rule;

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
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $localityService = new LocalityService($value);
        return $localityService->buildLocality();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('misc.geocode_not_found');
    }
}
