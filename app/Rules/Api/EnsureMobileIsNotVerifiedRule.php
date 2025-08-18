<?php

declare(strict_types=1);

namespace App\Rules\Api;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EnsureMobileIsNotVerifiedRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = auth()->user();

        // Check if the mobile matches the authenticated user's mobile, ISD code matches, and is verified
        if (
            $user->mobile === $value &&
            $user->isd_code === request()->isd_code &&
            ! is_null($user->mobile_verified_at)
        ) {
            $fail(__('basecode/api.mobile_already_yours'));
        }
    }
}
