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
    public function authorize()
    {
        // no authorization needed, because the users can only update themselves with this request.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => ['required', 'string', 'max:100', new RealName],
            'last_name' => ['required', 'string', 'max:100', new RealName],
            'birth_year' => 'required|numeric|min:1920|max:2020',
            'gender' => 'required|string|in:m,f',
            'email' => 'nullable|string|email|max:255|unique:users',
            'locality' => ['nullable', new GeonamesCodeExists],
        ];
    }
}
