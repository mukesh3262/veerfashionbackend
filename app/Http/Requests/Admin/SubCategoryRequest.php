<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class SubCategoryRequest extends FormRequest
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
            'parent_id' => ['required', Rule::exists(Category::class, 'id')->whereNull('deleted_at')->whereNull('parent_id')],
            'name' => ['required', 'string', 'min:3', 'max:255', Rule::unique(Category::class, 'name')->ignore($this->id, 'uuid')],
            'description' => ['required', 'string', 'min:3'],
            'category_icon' => [Rule::requiredIf(!$this->id), 'nullable', File::image(), 'max:10240'],
        ];
    }

    public function attributes()
    {
        return [
            'parent_id' => 'parent category',
        ];
    }
}
