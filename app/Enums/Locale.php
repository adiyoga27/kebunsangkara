<?php

namespace App\Enums;

/**
 * Languages of the public website. Each one lives under its own URL prefix, e.g. /id and /en.
 */
enum Locale: string
{
    case Indonesian = 'id';
    case English = 'en';

    public static function default(): self
    {
        return self::Indonesian;
    }

    public static function current(): self
    {
        return self::tryFrom(app()->getLocale()) ?? self::default();
    }

    public function isDefault(): bool
    {
        return $this === self::default();
    }

    public function label(): string
    {
        return match ($this) {
            self::Indonesian => 'Bahasa Indonesia',
            self::English => 'English',
        };
    }

    public function formatPrice(int $rupiah): string
    {
        return match ($this) {
            self::Indonesian => 'Rp '.number_format($rupiah, 0, ',', '.'),
            self::English => 'IDR '.number_format($rupiah),
        };
    }

    public function openGraph(): string
    {
        return match ($this) {
            self::Indonesian => 'id_ID',
            self::English => 'en_US',
        };
    }
}
