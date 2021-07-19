<?php

namespace App\Http\Requests\Auth;

use App\Rules\RealName;
use App\Rules\GeonamesCodeExists;
use Illuminate\Foundation\Http\FormRequest;

class CompleteRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // no authorization needed, because the users can only update themselves with this request.
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
            'date_of_birth' => [
                'required',
                'date',
                'after:' . today()->subYears(100)->toDateString(),
                'before:' . today()->subYears(5)->toDateString()
            ],
            'gender' => 'required|string|in:m,f',
            'email' => 'nullable|string|email|max:255|unique:users',
            'locality' => ['nullable', new GeonamesCodeExists],
        ];
    }
}
