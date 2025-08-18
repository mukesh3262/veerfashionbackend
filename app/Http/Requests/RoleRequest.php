<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
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
            'name' => ['required', 'min:4', 'max:255', Rule::unique(Role::class)->ignore($this->id)],
            'permissions' => ['required', 'array'],
            'permissions.*' => ['required', Rule::exists(Permission::class, 'name')],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => Str::lower($this->name),
        ]);
    }

    public function messages(): array
    {
        return [
            'permissions.required' => __('basecode/admin.atleast', ['entity' => 'permission', 'count' => 'one']),
        ];
    }
}
