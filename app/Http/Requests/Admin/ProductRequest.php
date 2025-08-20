<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class ProductRequest extends FormRequest
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
        $hasVariants = !empty($this->input('variants'));
        $isUpdate    = !empty($this->input('id')); // 🔹 check if product is being edited

        $rules = [
            // Basic info
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:3'],
            'base_price'  => ['required', 'numeric', 'min:0'],
            'category'    => ['required', 'integer', 'exists:categories,id'],

            // Product images (new uploads only)
            'product_images'   => ['array'],
            'product_images.*' => [
                File::image()
                    ->types(['jpg', 'jpeg', 'png', 'webp'])
                    ->max(10 * 1024), // 10 MB
            ],

            // Variants (array itself)
            'variants'   => ['nullable', 'array'],
            'variants.*.sku' => [
                'required',
                'string',
                'max:100',

                // ✅ Use Rule::unique with ignore
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[1];
                    $variantId = $this->input("variants.$index.id");
                    $rule = Rule::unique('product_variants', 'sku');
                    if ($variantId) {
                        $rule->ignore($variantId, 'uuid');
                    }
                    $validator = \Validator::make(
                        ['sku' => $value],
                        ['sku' => [$rule]]
                    );

                    if ($validator->fails()) {
                        $fail("The SKU has already been taken.");
                    }
                },

                // ✅ Prevent duplicates in request array
                function ($attribute, $value, $fail) {
                    $skus = collect($this->input('variants'))->pluck('sku');
                    if ($skus->duplicates()->contains($value)) {
                        $fail("The SKU must be unique among variants.");
                    }
                },
            ],
            'variants.*.brand'                => [$hasVariants ? 'required' : 'nullable', 'string', 'max:255'],
            'variants.*.price'                => [$hasVariants ? 'required' : 'nullable', 'numeric', 'min:0'],
            'variants.*.attributes.color'     => [$hasVariants ? 'required' : 'nullable', 'string', 'max:50'],
            'variants.*.attributes.size'      => [$hasVariants ? 'required' : 'nullable', 'string', 'max:50'],

            // Variant images
            'variants.*.variant_images'   => ['array', 'max:5'], // only new files
            'variants.*.variant_images.*' => [
                File::image()
                    ->types(['jpg', 'jpeg', 'png', 'webp'])
                    ->max(10 * 1024),
            ],

        ];

        // 🔹 Add custom logic for product images
        $rules['product_images'][] = function ($attribute, $value, $fail) use ($isUpdate) {
            $existing = $this->input('existing_product_images', []);
            $deleted  = $this->input('deleted_product_images', []);

            $remainingExisting = collect($existing)
                ->reject(fn($img) => in_array($img['id'], $deleted))
                ->count();

            $newCount = is_array($value) ? count($value) : 0;

            if ($isUpdate) {
                // Update → require at least one (remaining + new)
                if ($remainingExisting + $newCount === 0) {
                    $fail('At least one product image is required.');
                }
            } else {
                // Create → must upload at least one
                if ($newCount === 0) {
                    $fail('At least one product image is required.');
                }
            }
        };

        // 🔹 Add custom logic for each variant's images
        $rules['variants.*.variant_images'][] = function ($attribute, $value, $fail) use ($isUpdate) {
            $index     = (int) str_replace(['variants.', '.variant_images'], '', $attribute);
            $variants  = $this->input('variants', []);
            $variant   = $variants[$index] ?? null;

            if (!$variant) {
                return;
            }

            $existing = $variant['existing_variant_images'] ?? [];
            $deleted  = $variant['deleted_variant_images'] ?? [];

            $remainingExisting = collect($existing)
                ->reject(fn($img) => in_array($img['id'], $deleted))
                ->count();

            $newCount = is_array($value) ? count($value) : 0;

            if ($isUpdate) {
                if ($remainingExisting + $newCount === 0) {
                    $fail("Each variant must have at least one image.");
                }
            } else {
                if ($newCount === 0) {
                    $fail("Each variant must have at least one image.");
                }
            }
        };

        return $rules;
    }


    public function messages(): array
    {
        return [
            // Product images
            'product_images.required' => 'Please upload at least one product image.',
            'product_images.*.image'  => 'Product image must be a valid image file.',
            'product_images.*.mimes'  => 'Product images must be in jpg, jpeg, png, or webp format.',
            'product_images.*.max'    => 'Product image may not be greater than 10MB.',

            // Variants
            'variants.array' => 'Variants must be submitted as an array.',

            'variants.*.sku.required'  => 'The SKU field is required.',
            'variants.*.sku.unique'    => 'The SKU must be unique.',
            'variants.*.sku.max'       => 'The SKU may not be greater than 100 characters.',

            'variants.*.brand.required' => 'The brand field is required.',
            'variants.*.brand.max'      => 'The brand name may not be greater than 255 characters.',

            'variants.*.price.required' => 'The price field is required.',
            'variants.*.price.numeric'  => 'The price must be a valid number.',
            'variants.*.price.min'      => 'The price must be at least 0.',

            'variants.*.attributes.color.required' => 'The color field is required.',
            'variants.*.attributes.color.max'      => 'The color may not be greater than 50 characters.',

            'variants.*.attributes.size.required' => 'The size field is required.',
            'variants.*.attributes.size.max'      => 'The size may not be greater than 50 characters.',

            // Variant images
            'variants.*.variant_images.required' => 'Variant must have at least one image.',
            'variants.*.variant_images.array'    => 'Variant images must be uploaded as an array.',
            'variants.*.variant_images.max'      => 'You can upload a maximum of 5 images per variant.',
            'variants.*.variant_images.*.image'  => 'Variant image must be a valid image file.',
            'variants.*.variant_images.*.mimes'  => 'Variant images must be jpg, jpeg, png, or webp format.',
            'variants.*.variant_images.*.max'    => 'Variant image may not be greater than 10MB.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $errors = $validator->errors()->getMessages();

            foreach ($errors as $key => $messages) {
                // Collapse product_images.0 → product_images
                if (preg_match('/^(product_images)\.\d+$/', $key, $matches)) {
                    $parentKey = $matches[1];
                    foreach ($messages as $msg) {
                        $validator->errors()->add($parentKey, $msg);
                    }
                    $validator->errors()->forget($key); // remove child
                }

                // Collapse variants.0.variant_images.0 → variants.0.variant_images
                if (preg_match('/^(variants\.\d+\.variant_images)\.\d+$/', $key, $matches)) {
                    $parentKey = $matches[1];
                    foreach ($messages as $msg) {
                        $validator->errors()->add($parentKey, $msg);
                    }
                    $validator->errors()->forget($key); // remove child
                }
            }
        });
    }
}
