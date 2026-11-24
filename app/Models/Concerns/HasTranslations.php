<?php

namespace App\Models\Concerns;

use App\Enums\Locale;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

/**
 * Reads translatable attributes in the current language, stored as {"en": {"name": "..."}} in the translations column.
 * The regular columns hold the default language and are used when a translation is missing.
 */
trait HasTranslations
{
    /**
     * Attributes that can be translated, with their maximum length.
     *
     * @return array<string, int>
     */
    abstract public static function translatableAttributes(): array;

    /**
     * Attributes that must be translated before the record counts as available in a language.
     *
     * @return list<string>
     */
    abstract public static function requiredTranslations(): array;

    public function initializeHasTranslations(): void
    {
        $this->mergeCasts(['translations' => 'array']);
        $this->mergeFillable(['translations']);
    }

    public static function bootHasTranslations(): void
    {
        static::saving(function (self $model): void {
            $translations = array_filter(array_map(
                fn (mixed $fields): array => array_filter((array) $fields, filled(...)),
                $model->translations ?? [],
            ));

            if ($translations !== ($model->translations ?? [])) {
                $model->translations = $translations ?: null;
            }
        });
    }

    /**
     * @param  string  $key
     */
    public function getAttribute($key): mixed
    {
        $locale = Locale::current();

        if ($locale->isDefault() || ! is_string($key) || ! array_key_exists($key, static::translatableAttributes())) {
            return parent::getAttribute($key);
        }

        $translation = $this->translation($key, $locale);

        // Meta texts fall back to generated ones instead of another language.
        return $translation ?? (str_starts_with($key, 'meta_') ? null : parent::getAttribute($key));
    }

    public function translation(string $key, Locale $locale): ?string
    {
        $value = parent::getAttribute('translations')[$locale->value][$key] ?? null;

        return filled($value) ? $value : null;
    }

    public function isAvailableIn(Locale $locale): bool
    {
        return $locale->isDefault()
            || array_all(static::requiredTranslations(), fn (string $key): bool => $this->translation($key, $locale) !== null);
    }

    /**
     * @return list<Locale>
     */
    public function availableLocales(): array
    {
        return array_values(array_filter(Locale::cases(), $this->isAvailableIn(...)));
    }

    /**
     * Only records that can be read in the current language.
     *
     * @param  Builder<static>  $query
     */
    #[Scope]
    protected function availableInCurrentLocale(Builder $query): void
    {
        $locale = Locale::current();

        if ($locale->isDefault()) {
            return;
        }

        foreach (static::requiredTranslations() as $key) {
            $query->whereNotNull("translations->{$locale->value}->{$key}");
        }
    }
}
