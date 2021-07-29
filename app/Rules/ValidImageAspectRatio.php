<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Intervention\Image\ImageManagerStatic;

class ValidImageAspectRatio implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $img = ImageManagerStatic::make($value->getPathname());

        $img->orientate();

        $w = $img->width();
        $h = $img->height();

        return $w / $h > 0.25 && $w / $h < 4;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return __('validation.image_aspect_ratio');
    }
}
