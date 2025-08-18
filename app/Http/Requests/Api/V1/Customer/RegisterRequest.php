<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Customer;

use App\Enums\DeviceTypeEnum;
use App\Enums\UserRoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'min:3', 'max:255'],
            'last_name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'isd_code' => ['required'],
            'mobile' => ['required', Rule::unique('users', 'mobile')],
            'role' => ['required', Rule::in([UserRoleEnum::CUSTOMER, UserRoleEnum::SERVICE_PROVIDER])],
            'device_name' => ['required', 'max:255'],
            'device_type' => ['required', Rule::enum(DeviceTypeEnum::class)],
            'device_id' => ['required', 'max:255'],
            'device_token' => ['nullable'],
        ];
    }
}
