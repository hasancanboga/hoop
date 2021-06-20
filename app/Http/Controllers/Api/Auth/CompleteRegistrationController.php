<?php

namespace App\Http\Controllers\Api\Auth;

use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CompleteRegistrationRequest;

class CompleteRegistrationController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function store(CompleteRegistrationRequest $request)
    {
        if ($request->user()->hasCompletedRegistration()) {
            return response(message(__('auth.registration_already_completed')), 403);
        }
        $this->userService->completeRegistration($request);
    }
}
