<?php

declare(strict_types=1);

namespace App\Rules\Api;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetTokenRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $email = request()->email;

        // Check if reset mode is valid
        $this->validateResetMode($fail);

        // Fetch the password reset token for the given email
        $passwordResetToken = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        // If no token exists for the email, fail
        if (! $passwordResetToken) {
            $fail('basecode/api.no_request')->translate();

            return;
        }

        // Handle OTP reset mode
        if (config('auth.password_reset_mode') === 'otp') {
            $this->validateOtp($passwordResetToken, $value, $fail);
        }

        // Handle Link reset mode
        if (config('auth.password_reset_mode') === 'link') {
            $this->validateLink($passwordResetToken, $value, $fail);
        }
    }

    /**
     * Validate reset mode.
     */
    private function validateResetMode(Closure $fail): void
    {
        if (! in_array(config('auth.password_reset_mode'), config('auth.supported_reset_mechanisms'), true)) {
            $fail('basecode/api.password_reset_mismatch')->translate();
        }
    }

    /**
     * Validate OTP reset mode.
     */
    private function validateOtp(object $passwordResetToken, string $value, Closure $fail): void
    {
        // Check if OTP is not whitelisted
        if (! in_array($value, config('auth.whitelisted_otps'), true)) {
            // If the token matches, check if it is expired
            if ($this->isTokenExpired($passwordResetToken)) {
                $fail('basecode/api.expired_token')->translate();
            }
        }
    }

    /**
     * Validate Link reset mode.
     */
    private function validateLink(object $passwordResetToken, string $value, Closure $fail): void
    {
        // Check if the token matches
        if (! Hash::check($value, $passwordResetToken->token)) {
            $fail('validation.exists')->translate();

            return;
        }

        // If the token matches, check if it is expired
        if ($this->isTokenExpired($passwordResetToken)) {
            $fail('basecode/api.expired_token')->translate();
        }
    }

    /**
     * Check if the token is expired.
     */
    private function isTokenExpired(object $passwordResetToken): bool
    {
        return Carbon::parse($passwordResetToken->created_at)
            ->addMinutes(config('auth.passwords.users.expire'))
            ->isPast();
    }
}
