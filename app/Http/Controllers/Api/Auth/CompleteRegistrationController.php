<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CompleteRegistrationRequest;
use App\Services\UserService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class CompleteRegistrationController extends Controller
{

    public function store(CompleteRegistrationRequest $request):
    Response|Application|ResponseFactory
    {
        if ($request->user()->hasCompletedRegistration()) {
            return response(message(__('auth.registration_already_completed')), 409);
        }

        $userService = new UserService();
        $userService->completeRegistration($request);

        return response(null);
    }
}
