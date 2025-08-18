<?php

declare(strict_types=1);

namespace App\Rules\Api;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class VerifyOtpRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $verificationFor = request()->verification_for;
        $user = request()->user();

        $otp = $verificationFor === 'email' ? $user->email_otp : $user->mobile_otp;
        $otpExpiredAt = $verificationFor === 'email' ? $user->email_otp_expired_at : $user->mobile_otp_expired_at;

        if (optional($otpExpiredAt)->isPast()) {
            $fail(__('basecode/api.otp.expired'));
        }

        if ($otp !== $value && ! in_array($value, config('auth.whitelisted_otps'), true)) {
            $fail(__('basecode/api.otp.not_matched'));
        }
    }
}
