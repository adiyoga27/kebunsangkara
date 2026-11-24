<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\ValidatesTranslations;
use App\Models\Testimonial;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveTestimonialRequest extends FormRequest
{
    use ValidatesTranslations;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_published' => $this->boolean('is_published')]);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            ...$this->translationRules(Testimonial::class),
            'product_id' => ['nullable', 'integer', Rule::exists('products', 'id')],
            'name' => ['required', 'string', 'max:100'],
            'location' => ['nullable', 'string', 'max:100'],
            'content' => ['required', 'string', 'max:1000'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_image' => ['sometimes', 'boolean'],
            'is_published' => ['boolean'],
        ];
    }
}
