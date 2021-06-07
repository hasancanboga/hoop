<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\Auth\CompleteRegistrationRequest;

class UserService
{
    public function completeRegistration(CompleteRegistrationRequest $request)
    {
        dd($request->validated());
        // $request->user()->update([
        //     'first_name' => $request->first_name,
        //     'last_name' => $request->last_name,
        //     // 'date_of_birth' => $request->date_of_birth,
        //     'gender' => $request->gender,
        //     'email' => $request->email,
        //     'city' => $request->city,
        // ]);
        event(new Registered($request->user()));
    }
}
