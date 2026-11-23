<?php

namespace App\Enums;

enum FruitCategory: string
{
    case Durian = 'durian';
    case Alpukat = 'alpukat';
    case Jeruk = 'jeruk';
    case Manggis = 'manggis';

    public function label(): string
    {
        return match ($this) {
            self::Durian => 'Durian',
            self::Alpukat => 'Alpukat',
            self::Jeruk => 'Jeruk',
            self::Manggis => 'Manggis',
        };
    }

    public function emoji(): string
    {
        return match ($this) {
            self::Durian => '🍈',
            self::Alpukat => '🥑',
            self::Jeruk => '🍊',
            self::Manggis => '🟣',
        };
    }

    /**
     * Photo used when a product of this category has no uploaded image.
     */
    public function imagePath(): string
    {
        return match ($this) {
            self::Durian => 'images/kebun/durian-matang-di-pohon.webp',
            self::Alpukat => 'images/kebun/alpukat-di-pohon.webp',
            self::Jeruk => 'images/kebun/petani-memetik-jeruk.webp',
            self::Manggis => 'images/kebun/buah-manggis.webp',
        };
    }

    public function tagline(): string
    {
        return match ($this) {
            self::Durian => 'Matang pohon, manis legit, aroma kuat',
            self::Alpukat => 'Daging tebal, lembut seperti mentega',
            self::Jeruk => 'Segar, manis, kaya air',
            self::Manggis => 'Ratu buah, manis-asam menyegarkan',
        };
    }

    public function seoTitle(): string
    {
        return match ($this) {
            self::Durian => 'Jual Durian Bali Asli Matang Pohon – Harga Terbaru',
            self::Alpukat => 'Jual Alpukat Mentega Bali Segar – Harga Terbaru',
            self::Jeruk => 'Jual Jeruk Bali Segar dari Kebun – Harga Terbaru',
            self::Manggis => 'Jual Manggis Bali Segar dari Kebun – Harga Terbaru',
        };
    }

    public function seoDescription(): string
    {
        return match ($this) {
            self::Durian => 'Beli durian asli Bali dari Kebun Sangkara: durian lokal & Montong matang pohon, garansi ganti jika busuk. Antar ke seluruh Bali, pesan via WhatsApp.',
            self::Alpukat => 'Beli alpukat mentega segar dari Kebun Sangkara Bali. Daging tebal, pulen, cocok untuk jus & salad. Antar ke seluruh Bali, pesan via WhatsApp.',
            self::Jeruk => 'Beli jeruk segar dari kebun Bali di Kebun Sangkara. Manis, kaya air, dipetik langsung dari pohon. Antar ke seluruh Bali, pesan via WhatsApp.',
            self::Manggis => 'Beli manggis segar dari Kebun Sangkara Bali. Kulit ungu mulus, daging putih manis-asam. Antar ke seluruh Bali, pesan via WhatsApp.',
        };
    }

    public function intro(): string
    {
        return match ($this) {
            self::Durian => 'Durian dari kebun kami dipanen saat matang alami di pohon, bukan diperam. Hasilnya daging buah yang tebal, manis legit, dengan aroma khas durian Bali.',
            self::Alpukat => 'Alpukat kami dipetik dari pohon saat sudah tua dan dibiarkan matang alami. Daging buahnya tebal, lembut, dan gurih — pas untuk jus, salad, atau roti panggang.',
            self::Jeruk => 'Jeruk segar dipetik langsung dari pohon di kebun Bali. Rasanya manis menyegarkan dengan kandungan air yang melimpah — cocok dimakan langsung atau dijadikan jus.',
            self::Manggis => 'Manggis dijuluki "ratu buah". Daging buahnya putih bersih dengan rasa manis-asam yang menyegarkan. Kami memilih manggis dengan kulit mulus dan buah yang padat.',
        };
    }
}
