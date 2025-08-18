<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use App\Enums\DeviceTypeEnum;
use App\Enums\SocialTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SocialLoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'social_id' => ['required'],
            'name' => ['required'],
            'email' => ['email', 'nullable'],
            'social_type' => ['required', Rule::enum(SocialTypeEnum::class)],
            'device_name' => ['required', 'max:255'],
            'device_type' => ['required', Rule::enum(DeviceTypeEnum::class)],
            'device_id' => ['required', 'max:255'],
        ];
    }
}
