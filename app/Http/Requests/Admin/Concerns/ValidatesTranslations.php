<?php

namespace App\Http\Requests\Admin\Concerns;

use App\Enums\Locale;

trait ValidatesTranslations
{
    /**
     * Optional rules for every translatable attribute of the given model.
     *
     * @param  class-string  $model
     * @return array<string, list<string>>
     */
    protected function translationRules(string $model): array
    {
        $attributes = $model::translatableAttributes();
        $locales = array_filter(Locale::cases(), fn (Locale $locale): bool => ! $locale->isDefault());

        $rules = ['translations' => ['sometimes', 'array:'.implode(',', array_column($locales, 'value'))]];

        foreach ($locales as $locale) {
            $rules["translations.{$locale->value}"] = ['sometimes', 'array:'.implode(',', array_keys($attributes))];

            foreach ($attributes as $attribute => $maxLength) {
                $rules["translations.{$locale->value}.{$attribute}"] = ['nullable', 'string', "max:{$maxLength}"];
            }
        }

        return $rules;
    }
}
