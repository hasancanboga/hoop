<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\Auth\CompleteRegistrationRequest;

class UserService
{
    public function completeRegistration(CompleteRegistrationRequest $request)
    {
        if ($geonamesCode = $request->validated()['locality']) {
            $localityService = new LocalityService($geonamesCode);
            $localityService->store($request->user());
        }

        $request->user()->update($request->validated());
        $request->user()->generateUniqueUsername();
        event(new Registered($request->user()));
    }
}
