<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\ValidatesTranslations;
use App\Models\FruitCategory;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SaveFruitCategoryRequest extends FormRequest
{
    use ValidatesTranslations;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => $this->resolveSlug(),
            'sort_order' => (int) $this->input('sort_order', 0),
        ]);
    }

    /**
     * The slug of a saved category is fixed, because its products and page URL depend on it.
     */
    private function resolveSlug(): string
    {
        $category = $this->route('category');

        if ($category instanceof FruitCategory) {
            return $category->slug;
        }

        if ($this->filled('slug')) {
            return Str::slug($this->string('slug')->value());
        }

        return rtrim(Str::substr(Str::slug($this->string('name')->value()), 0, FruitCategory::MAX_SLUG_LENGTH), '-');
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            ...$this->translationRules(FruitCategory::class),
            'name' => ['required', 'string', 'max:50', Rule::unique('fruit_categories', 'name')->ignore($this->route('category'))],
            'slug' => ['required', 'string', 'max:'.FruitCategory::MAX_SLUG_LENGTH, Rule::unique('fruit_categories', 'slug')->ignore($this->route('category'))],
            'emoji' => ['required', 'string', 'max:16'],
            'tagline' => ['required', 'string', 'max:100'],
            'intro' => ['required', 'string', 'max:500'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_image' => ['sometimes', 'boolean'],
            'sort_order' => ['integer', 'min:0', 'max:65000'],
            'meta_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:170'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.unique' => 'Kategori dengan nama ini sudah ada.',
            'slug.unique' => 'Slug URL ini sudah dipakai kategori lain. Gunakan slug lain.',
        ];
    }
}
