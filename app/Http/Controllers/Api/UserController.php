<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Rules\ValidImageAspectRatio;
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

        $request->user()->update($validated);

        return response($request->user());
    }

    public function updateProfileImage(Request $request): Response|Application|ResponseFactory
    {
        $request->validate([
            'profile_image' => ['required', 'image', 'max:5000', new ValidImageAspectRatio],
        ]);

        $tempImage = [
            'collection' => 'profile_images',
            'type' => 'image',
            'temp_file_name' => $request->file('profile_image')
                ->store('temp/profile-images', 'local')
        ];

        $request->user()->deleteProfileImage();

        $image = $request->user()->profile_image()->create($tempImage);

        $imageService = new ImageService($image);
        try {
            $imageService->store();
        } catch (Exception $e) {
            return response(message($e->getMessage()), 400);
        }
        return response(null);
    }

    public function deleteProfileImage(Request $request)
    {
        $request->user()->deleteProfileImage();
    }
}
