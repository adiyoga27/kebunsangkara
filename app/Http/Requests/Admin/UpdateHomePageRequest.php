<?php

namespace App\Http\Requests\Admin;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateHomePageRequest extends FormRequest
{
    private const MAX_MARQUEE_LINES = 20;

    private const MAX_MARQUEE_LINE_LENGTH = 60;

    public function authorize(): bool
    {
        return $this->user()?->can('administer') ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'home_marquee_enabled' => $this->boolean('home_marquee_enabled') ? '1' : '0',
            'home_marquee' => $this->withoutBlankLines('home_marquee'),
            'home_marquee_en' => $this->withoutBlankLines('home_marquee_en'),
        ]);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'home_marquee_enabled' => ['required', 'in:0,1'],
            'home_marquee' => ['bail', 'required', 'string', $this->marqueeLines(...)],
            'home_marquee_en' => ['bail', 'nullable', 'string', $this->marqueeLines(...)],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return ['home_marquee' => 'tulisan bergerak', 'home_marquee_en' => 'tulisan bergerak (English)'];
    }

    private function marqueeLines(string $attribute, mixed $value, Closure $fail): void
    {
        $lines = explode("\n", (string) $value);

        if (count($lines) > self::MAX_MARQUEE_LINES) {
            $fail('Maksimal '.self::MAX_MARQUEE_LINES.' tulisan.');
        }

        foreach ($lines as $index => $line) {
            if (mb_strlen($line) > self::MAX_MARQUEE_LINE_LENGTH) {
                $fail('Baris '.($index + 1).' lebih dari '.self::MAX_MARQUEE_LINE_LENGTH.' karakter.');
            }
        }
    }

    private function withoutBlankLines(string $key): mixed
    {
        $value = $this->input($key);

        if (! is_string($value)) {
            return $value;
        }

        return Str::of($value)->explode("\n")->map(fn (string $line): string => trim($line))->filter(filled(...))->implode("\n") ?: null;
    }
}
