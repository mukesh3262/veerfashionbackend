<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EditorRule implements ValidationRule
{
    public function __construct(
        protected bool $required = false,
        protected ?int $minLength = null,
        protected ?int $maxLength = null,
        protected bool $trim = false,
    ) {}

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $this->required) {
            return;
        }

        // Strip HTML tags from the input
        $plainText = strip_tags($value);

        // Convert HTML entities to plain text
        $plainText = html_entity_decode($plainText, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Trim the input if necessary
        if ($this->trim) {
            $plainText = trim($plainText);
        }

        // Check if the plain text length is greater than or equal to the minimum length
        if ($this->minLength && mb_strlen($plainText) < $this->minLength) {
            $fail('The :attribute must be at least :minLength characters.')
                ->translate([
                    'minLength' => $this->minLength,
                ]);
        }

        // Check if the plain text length is less than or equal to the maximum length
        if ($this->maxLength && mb_strlen($plainText) > $this->maxLength) {
            $fail('The :attribute may not be greater than :maxLength characters.')
                ->translate([
                    'maxLength' => $this->maxLength,
                ]);
        }
    }
}
