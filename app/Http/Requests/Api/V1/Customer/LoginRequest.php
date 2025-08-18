<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Customer;

use App\Enums\DeviceTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class LoginRequest extends FormRequest
{
    private bool $isUsernamePhone = false;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'min:5', $this->isUsernamePhone ? 'phone:AUTO' : ''],
            'device_name' => ['required', 'max:255'],
            'device_type' => ['required', Rule::enum(DeviceTypeEnum::class)],
            'device_id' => ['required', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.phone' => __('basecode/api.phone'),
        ];
    }

    protected function prepareForValidation(): void
    {
        if (! $this->username) {
            throw new HttpResponseException(
                FacadesResponse::error(
                    message: __('validation.required', ['attribute' => __('label.username')]),
                    statusCode: SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY
                )
            );
        }

        if (
            $this->username[0] === '+'
            && is_numeric($this->username)
            && in_array('mobile', config('auth.supported_logins'), true)
        ) {
            $type = 'mobile';
            $this->isUsernamePhone = true;
        } elseif (
            ! filter_var($this->username, FILTER_VALIDATE_EMAIL)
            && in_array('username', config('auth.supported_logins'), true)
        ) {
            $type = 'username';
        }

        $this->merge([
            'username' => mb_strtolower($this->username),
            'login_type' => $type ?? 'email',
        ]);
    }
}
