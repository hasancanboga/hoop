<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use App\Services\SmsService;
use App\Exceptions\SmsException;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

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
            'phone' => 'required|string|phone',
            'phone_country' => 'required_with:phone',
        ];
    }

    /** Fire off the OTP after validating the phone.
     * 
     */
    public function loginOrRegister()
    {
        $this->ensureIsNotRateLimited();

        $user = User::firstOrCreate([
            'phone' => phone($this->phone, $this->phone_country)->formatE164(),
        ]);

        $otp = otp();
        $user->otp = bcrypt($otp);
        $user->otp_expiry = now()->addSeconds(120);
        $user->save();

        $this->setUserResolver(fn () => $user);

        try {
            $this->smsService->send($user->phone, $otp);
        } catch (SmsException $e) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'phone' => __('misc.unknown_error'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }


    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
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
    public function throttleKey()
    {
        return Str::lower($this->input('phone')) . '|' . $this->ip();
    }
}
