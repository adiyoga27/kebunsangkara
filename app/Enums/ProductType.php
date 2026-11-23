<?php

namespace App\Enums;

enum ProductType: string
{
    case Buah = 'buah';
    case Bibit = 'bibit';

    public function label(): string
    {
        return match ($this) {
            self::Buah => 'Buah Segar',
            self::Bibit => 'Bibit',
        };
    }

    public function emoji(): string
    {
        return match ($this) {
            self::Buah => '🧺',
            self::Bibit => '🌱',
        };
    }
}
