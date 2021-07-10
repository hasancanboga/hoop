<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class ConfirmOtpRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'phone' => 'required|string',
            'otp' => 'required|string',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $this->ensureIsNotRateLimited();

        $this->ensureOtpNotExpired();
        
        if (!Auth::attempt(
            [
                'phone' => $this->phone,
                'password' => $this->otp
            ],
            $this->boolean('remember')
        )) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'phone' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the OTP request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'phone' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Ensure the OTP is not expired
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureOtpNotExpired()
    {
        $user = User::where('phone', $this->phone)->first();
        if (now() > Carbon::createFromTimeString($user->otp_expiry)) {
            logger('throwing');
            throw ValidationException::withMessages([
                'phone' => __('auth.otp_expired'),
            ]);
        }
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->input('phone')) . '|' . $this->ip();
    }
}
