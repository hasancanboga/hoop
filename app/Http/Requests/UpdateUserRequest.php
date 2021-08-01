<?php

namespace App\Http\Requests;

use App\Rules\GeonamesCodeExists;
use App\Rules\RealName;
use App\Rules\ValidImageAspectRatio;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @noinspection PhpArrayShapeAttributeCanBeAddedInspection
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100', new RealName],
            'last_name' => ['required', 'string', 'max:100', new RealName],
            'username' => [
                'string',
                'required',
                'max:255',
            ],
            'date_of_birth' => [
                'required',
                'date',
                'after:' . today()->subYears(100)->toDateString(),
                'before:' . today()->subYears(5)->toDateString()
            ],
            'gender' => ['required', 'string', 'in:m,f'],
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user()),
            ],
            'locality' => ['nullable', new GeonamesCodeExists],
            'profile_image' => ['image', 'max:5000', new ValidImageAspectRatio],
        ];
    }
}
