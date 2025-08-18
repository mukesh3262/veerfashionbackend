<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SmtpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'mailer' => ['required', 'in:'.implode(',', array_keys(config('mail.mailers')))],
            'host' => ['required', 'max:255', 'regex:/^(?!:\/\/)(?=.{1,255}$)((.{1,63}\.){1,127}[a-z0-9-]+\.[a-z]{2,63}|(\d{1,3}\.){3}\d{1,3})$/i'],
            'port' => ['required', 'integer', 'between:1,65535'],
            'username' => ['required', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
            'encryption' => ['required', 'in:tls,ssl,null'],
            'from_address' => ['required', 'email', 'max:254'],
            'from_name' => ['required', 'string', 'max:100'],
        ];
    }
}
