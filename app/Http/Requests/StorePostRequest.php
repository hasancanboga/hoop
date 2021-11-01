<?php

namespace App\Http\Requests;

use App\Rules\ValidImageAspectRatio;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
        return[
            'body' => ['required', 'max:255'],
            'parent_id' => ['integer'],
            'images' => ['array', 'max:1'],
            'images.*' => ['image', 'max:5000', new ValidImageAspectRatio],
            'videos' => ['array', 'max:1'],
            'videos.*' => ['mimetypes:video/x-ms-asf,video/x-flv,video/mp4,application/x-mpegURL,video/MP2T,video/3gpp,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/avi', 'max:128000'],
        ];
    }
}
