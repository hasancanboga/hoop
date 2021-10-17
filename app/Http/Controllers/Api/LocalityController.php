<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MenaraSolutions\Geographer\Country;

class LocalityController extends Controller
{
    public function cities(): array
    {
        return Country::build('TR')
            ->getStates()->sortBy('isoCode')->toArray();
    }
}
