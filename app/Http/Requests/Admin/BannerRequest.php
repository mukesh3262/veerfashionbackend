<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class BannerRequest extends FormRequest
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
            'title' => ['required'],
            'subtitle' => ['nullable'],
            'description' => ['nullable'],
            'position' => ['nullable'],
            'href' => ['nullable'],
            'image' => [Rule::requiredIf(!$this->id), 'nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:10240'],
            'is_active' => ['nullable']
        ];
    }
   
}
