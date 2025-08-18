<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use App\Rules\Api\EnsureEmailIsNotVerifiedRule;
use App\Rules\Api\EnsureMobileIsNotVerifiedRule;
use Illuminate\Foundation\Http\FormRequest;

class SendOTPRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'verification_for' => ['required', 'in:email,mobile'],
            'email' => ['required_if:verification_for,email', 'email', new EnsureEmailIsNotVerifiedRule],
            'isd_code' => ['required_if:verification_for,mobile'],
            'mobile' => ['required_if:verification_for,mobile', new EnsureMobileIsNotVerifiedRule],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['email' => mb_strtolower($this?->email ?? '')]);
    }
}
