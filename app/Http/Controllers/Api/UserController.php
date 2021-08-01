<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Rules\GeonamesCodeExists;
use App\Rules\RealName;
use App\Rules\ValidImageAspectRatio;
use App\Services\ImageService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

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
            $imageService = new ImageService($request->file('profile_image'));
            $validated['profile_image'] = $imageService->store('profile_images');
        }

        $request->user()->update($validated);

        return response($request->user());
    }


}
