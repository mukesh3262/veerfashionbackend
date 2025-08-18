<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', Rule::unique(Admin::class)->ignore($this->id)],
            'role' => ['required', 'string', 'max:255', Rule::exists(Role::class, 'name')],
        ];
    }
}
