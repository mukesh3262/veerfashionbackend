<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Setting;
use Illuminate\Foundation\Http\FormRequest;

class MobileVersionRequest extends FormRequest
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
            'android' => ['required', 'array'],
            'android.version' => [
                'required',
                'integer',
                'min:0',
                function ($attribute, $value, $fail) {
                    if (! Setting::whereVersionShouldBeGreater('android', $value)->count()) {
                        $fail(__('basecode/admin.app_version_update_error'));
                    }
                },
            ],
            'android.force_updateable' => ['required', 'boolean'],

            'ios' => ['required', 'array'],
            'ios.version' => [
                'required',
                'integer',
                'min:0',
                function ($attribute, $value, $fail) {
                    if (! Setting::whereVersionShouldBeGreater('ios', $value)->count()) {
                        $fail(__('basecode/admin.app_version_update_error'));
                    }
                },
            ],
            'ios.force_updateable' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'android.version.required' => __('validation.required', ['attribute' => 'Android version']),
            'android.version.min' => __('validation.min.numeric', ['attribute' => 'Android version', 'min' => 0]),
            'android.force_updateable.required' => __('validation.required', ['attribute' => 'Android force updateable']),
            'android.force_updateable.boolean' => __('validation.boolean', ['attribute' => 'Android force updateable']),

            'ios.version.required' => __('validation.required', ['attribute' => 'iOS version']),
            'ios.version.min' => __('validation.min.numeric', ['attribute' => 'iOS version', 'min' => 0]),
            'ios.force_updateable.required' => __('validation.required', ['attribute' => 'iOS force updateable']),
            'ios.force_updateable.boolean' => __('validation.boolean', ['attribute' => 'iOS force updateable']),
        ];
    }
}
