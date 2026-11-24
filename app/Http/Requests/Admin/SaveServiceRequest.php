<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\ValidatesTranslations;
use App\Models\Service;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SaveServiceRequest extends FormRequest
{
    use ValidatesTranslations;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_published' => $this->boolean('is_published'),
            'sort_order' => (int) $this->input('sort_order', 0),
        ]);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            ...$this->translationRules(Service::class),
            'name' => ['required', 'string', 'max:120'],
            'summary' => ['required', 'string', 'max:300'],
            'description' => ['required', 'string', 'max:10000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_image' => ['sometimes', 'boolean'],
            'sort_order' => ['integer', 'min:0', 'max:65000'],
            'is_published' => ['boolean'],
        ];
    }
}
