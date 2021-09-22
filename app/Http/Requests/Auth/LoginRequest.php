<?php

namespace App\Http\Requests\Auth;

use App\Exceptions\SmsException;
use App\Models\User;
use App\Services\SmsService;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
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
            'phone' => 'required|string|phone',
            'phone_country' => 'required_with:phone',
        ];
    }

    /** Fire off the OTP after validating the phone.
     *
     * @throws ValidationException
     * @noinspection PhpUndefinedFieldInspection
     */
    public function loginOrRegister(): Response|Application|ResponseFactory
    {
        $this->ensureIsNotRateLimited();

        $user = User::firstOrCreate([
            'phone' => phone($this->phone, $this->phone_country)->formatE164(),
        ]);

        $otp = otp();
        $user->otp = bcrypt($otp);
        $user->otp_expiry = now()->addSeconds(120);
        $user->save();

        $this->setUserResolver(fn() => $user);

        $smsService = new SmsService();

        try {
            $smsService->send($user->phone, $otp);
        } catch (SmsException) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'phone' => __('misc.unknown_error'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        return response(['phone' => $user->phone]);
    }


    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 3)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'phone' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey(): string
    {
        return Str::lower($this->input('phone')) . '|' . $this->ip();
    }
}
