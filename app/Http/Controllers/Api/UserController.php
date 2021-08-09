<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\ImageService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{

    public function self()
    {
        return request()->user();
    }

    public function show(User $user): User
    {
        return $user;
    }

    public function update(UpdateUserRequest $request): Response|Application|ResponseFactory
    {
        $validated = $request->validated();

        if (request('profile_image')) {

            $imageService = new ImageService(
                $request->file('profile_image'),
                'profile_images'
            );

            try {
                $validated['profile_image'] = $imageService->store();
                $request->user()->deleteProfileImage();
            } catch (Exception $e) {
                return response(message($e->getMessage()), 400);
            }
        }

        $request->user()->update($validated);

        return response($request->user());
    }

    public function deleteProfileImage(Request $request)
    {
        $request->user()->deleteProfileImage();
    }
}
