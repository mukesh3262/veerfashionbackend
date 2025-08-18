<?php

declare(strict_types=1);

namespace App\Rules\Api;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EnsureEmailIsNotVerifiedRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = auth()->user();
        // Fail validation if the email is already verified and belongs to the user
        if ($user->email === $value && ! is_null($user->email_verified_at)) {
            $fail(__('basecode/api.email_already_yours'));
        }
    }
}
