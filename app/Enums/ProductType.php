<?php

namespace App\Enums;

enum ProductType: string
{
    case Buah = 'buah';
    case Bibit = 'bibit';
    case Pupuk = 'pupuk';
    case Kesehatan = 'kesehatan';

    public function label(): string
    {
        return match ($this) {
            self::Buah => 'Hasil Perkebunan',
            self::Bibit => 'Bibit',
            self::Pupuk => 'Pupuk',
            self::Kesehatan => 'Produk Kesehatan',
        };
    }

    public function emoji(): string
    {
        return match ($this) {
            self::Buah => '🧺',
            self::Bibit => '🌱',
            self::Pupuk => '🌾',
            self::Kesehatan => '🌿',
        };
    }

    /**
     * Whether products of this type belong to a fruit category.
     */
    public function hasFruitCategory(): bool
    {
        return match ($this) {
            self::Buah, self::Bibit => true,
            self::Pupuk, self::Kesehatan => false,
        };
    }

    /**
     * @return list<self>
     */
    public static function withFruitCategory(): array
    {
        return array_values(array_filter(self::cases(), fn (self $type): bool => $type->hasFruitCategory()));
    }
}
