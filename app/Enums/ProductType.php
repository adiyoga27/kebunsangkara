<?php

namespace App\Enums;

enum ProductType: string
{
    case Buah = 'buah';
    case Bibit = 'bibit';
    case Pupuk = 'pupuk';
    case Obat = 'obat';
    case Kesehatan = 'kesehatan';

    public function label(): string
    {
        return match ($this) {
            self::Buah => __('Hasil Perkebunan'),
            self::Bibit => __('Bibit'),
            self::Pupuk => __('Pupuk'),
            self::Obat => __('Obat Pertanian'),
            self::Kesehatan => __('Produk Kesehatan'),
        };
    }

    public function emoji(): string
    {
        return match ($this) {
            self::Buah => '🧺',
            self::Bibit => '🌱',
            self::Pupuk => '🌾',
            self::Obat => '🧴',
            self::Kesehatan => '🌿',
        };
    }

    /**
     * Unit prefilled in the admin form for new products.
     */
    public function defaultPriceUnit(): string
    {
        return match ($this) {
            self::Buah => 'per kg',
            self::Bibit => 'per batang',
            self::Pupuk => 'per karung',
            self::Obat => 'per botol',
            self::Kesehatan => 'per kemasan',
        };
    }

    /**
     * Example product name shown in the admin form.
     */
    public function exampleName(): string
    {
        return match ($this) {
            self::Buah => 'Durian Montong Bali, Alpukat Mentega',
            self::Bibit => 'Bibit Durian Montong Okulasi',
            self::Pupuk => 'Pupuk Kompos Organik',
            self::Obat => 'Fungisida Organik, Insektisida Nabati',
            self::Kesehatan => 'Madu Murni',
        };
    }

    /**
     * Whether products of this type belong to a fruit category.
     */
    public function hasFruitCategory(): bool
    {
        return match ($this) {
            self::Buah, self::Bibit => true,
            self::Pupuk, self::Obat, self::Kesehatan => false,
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
