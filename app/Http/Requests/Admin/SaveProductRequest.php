<?php

namespace App\Http\Requests\Admin;

use App\Enums\FruitCategory;
use App\Enums\ProductType;
use App\Http\Requests\Admin\Concerns\ValidatesTranslations;
use App\Models\Product;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class SaveProductRequest extends FormRequest
{
    use ValidatesTranslations;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'category' => $this->needsFruitCategory() ? $this->input('category') : null,
            'slug' => Str::slug($this->input('slug') ?: $this->input('name')),
            'is_available' => $this->boolean('is_available'),
            'is_featured' => $this->boolean('is_featured'),
            'sort_order' => (int) $this->input('sort_order', 0),
        ]);
    }

    private function needsFruitCategory(): bool
    {
        $type = $this->input('type');

        return (is_string($type) ? ProductType::tryFrom($type) : null)?->hasFruitCategory() ?? true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            ...$this->translationRules(Product::class),
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['required', 'string', 'max:160', 'alpha_dash', Rule::unique('products', 'slug')->ignore($this->route('product'))],
            'category' => [Rule::requiredIf($this->needsFruitCategory()), 'nullable', Rule::enum(FruitCategory::class)],
            'type' => ['required', Rule::enum(ProductType::class)],
            'propagation_method' => ['nullable', 'string', 'max:50'],
            'plant_height' => ['nullable', 'string', 'max:50'],
            'variety' => ['nullable', 'string', 'max:100'],
            'origin' => ['nullable', 'string', 'max:100'],
            'short_description' => ['required', 'string', 'max:300'],
            'description' => ['required', 'string', 'max:20000'],
            'price' => ['required', 'integer', 'min:0', 'max:100000000'],
            'price_unit' => ['required', 'string', 'max:50'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_image' => ['sometimes', 'boolean'],
            'images' => ['sometimes', 'array', 'max:'.Product::MAX_PHOTOS],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_images' => ['sometimes', 'array'],
            'remove_images.*' => ['integer', Rule::exists('product_images', 'id')->where('product_id', $this->route('product')?->getKey())],
            'is_available' => ['boolean'],
            'is_featured' => ['boolean'],
            'sort_order' => ['integer', 'min:0', 'max:65000'],
            'meta_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:170'],
        ];
    }

    /**
     * @return list<callable(Validator): void>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->hasAny(['image', 'images', 'images.*', 'remove_images', 'remove_images.*'])) {
                    return;
                }

                $product = $this->route('product');
                $keepsMainPhoto = $this->hasFile('image') || ($product?->image_path && ! $this->boolean('remove_image'));
                $keptPhotos = $product?->images()->whereNotIn('id', $this->input('remove_images', []))->count() ?? 0;

                if ((int) $keepsMainPhoto + $keptPhotos + count($this->file('images', [])) > Product::MAX_PHOTOS) {
                    $validator->errors()->add('images', 'Maksimal '.Product::MAX_PHOTOS.' foto per produk, termasuk foto utama.');
                }
            },
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'images' => 'foto galeri',
            'images.*' => 'foto galeri',
        ];
    }
}
