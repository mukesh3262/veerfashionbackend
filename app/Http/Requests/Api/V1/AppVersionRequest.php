<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use App\Enums\DeviceTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AppVersionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'platform' => [
                'required',
                Rule::enum(DeviceTypeEnum::class)->only([DeviceTypeEnum::IOS, DeviceTypeEnum::ANDROID]),
            ],
            'version' => [
                'required',
            ],
        ];
    }
}
