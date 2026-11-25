<?php

namespace App\Enums;

/**
 * Lines of business outside the fruit categories that have their own page.
 */
enum Offering: string
{
    case Pupuk = 'pupuk';
    case ObatPertanian = 'obat-pertanian';
    case ProdukKesehatan = 'produk-kesehatan';
    case Service = 'service';

    public function label(): string
    {
        return match ($this) {
            self::Pupuk => __('Pupuk'),
            self::ObatPertanian => __('Obat Pertanian'),
            self::ProdukKesehatan => __('Produk Kesehatan'),
            self::Service => __('Layanan Kami'),
        };
    }

    /**
     * The subject of a question about this offering, e.g. "Tanya Layanan".
     */
    public function topic(): string
    {
        return $this === self::Service ? __('Layanan') : $this->label();
    }

    public function emoji(): string
    {
        return match ($this) {
            self::Pupuk => '🌾',
            self::ObatPertanian => '🧴',
            self::ProdukKesehatan => '🌿',
            self::Service => '🛠️',
        };
    }

    /**
     * Catalog products listed on this page, if it sells any.
     */
    public function productType(): ?ProductType
    {
        return match ($this) {
            self::Pupuk => ProductType::Pupuk,
            self::ObatPertanian => ProductType::Obat,
            self::ProdukKesehatan => ProductType::Kesehatan,
            self::Service => null,
        };
    }

    public function intro(): string
    {
        return match ($this) {
            self::Pupuk => __('Informasi pupuk dari Kebun Sangkara. Tanyakan jenis, harga, dan ketersediaan terbaru melalui WhatsApp.'),
            self::ObatPertanian => __('Informasi obat pertanian dari Kebun Sangkara. Tanyakan jenis, harga, dan ketersediaan terbaru melalui WhatsApp.'),
            self::ProdukKesehatan => __('Informasi produk kesehatan dari Kebun Sangkara. Tanyakan pilihan produk, harga, dan ketersediaan terbaru melalui WhatsApp.'),
            self::Service => __('Informasi layanan dari Kebun Sangkara. Ceritakan kebutuhan Anda dan tanyakan layanan yang tersedia melalui WhatsApp.'),
        };
    }
}
