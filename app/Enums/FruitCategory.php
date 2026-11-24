<?php

namespace App\Enums;

enum FruitCategory: string
{
    case Durian = 'durian';
    case Alpukat = 'alpukat';
    case Manggis = 'manggis';

    public function label(): string
    {
        return match ($this) {
            self::Durian => __('Durian'),
            self::Alpukat => __('Alpukat'),
            self::Manggis => __('Manggis'),
        };
    }

    public function emoji(): string
    {
        return match ($this) {
            self::Durian => '🍈',
            self::Alpukat => '🥑',
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
            self::Manggis => 'images/kebun/buah-manggis.webp',
        };
    }

    public function tagline(): string
    {
        return match ($this) {
            self::Durian => __('Matang pohon, manis legit, aroma kuat'),
            self::Alpukat => __('Daging tebal, lembut seperti mentega'),
            self::Manggis => __('Ratu buah, manis-asam menyegarkan'),
        };
    }

    public function seoTitle(): string
    {
        return match ($this) {
            self::Durian => __('Jual Durian Bali Asli Matang Pohon – Harga Terbaru'),
            self::Alpukat => __('Jual Alpukat Mentega Bali Segar – Harga Terbaru'),
            self::Manggis => __('Jual Manggis Bali Segar dari Kebun – Harga Terbaru'),
        };
    }

    public function seoDescription(): string
    {
        return match ($this) {
            self::Durian => __('Beli durian asli Bali dari Kebun Sangkara: durian lokal & Montong matang pohon, garansi ganti jika busuk. Antar ke seluruh Bali, pesan via WhatsApp.'),
            self::Alpukat => __('Beli alpukat mentega segar dari Kebun Sangkara Bali. Daging tebal, pulen, cocok untuk jus & salad. Antar ke seluruh Bali, pesan via WhatsApp.'),
            self::Manggis => __('Beli manggis segar dari Kebun Sangkara Bali. Kulit ungu mulus, daging putih manis-asam. Antar ke seluruh Bali, pesan via WhatsApp.'),
        };
    }

    public function intro(): string
    {
        return match ($this) {
            self::Durian => __('Durian dari kebun kami dipanen saat matang alami di pohon, bukan diperam. Hasilnya daging buah yang tebal, manis legit, dengan aroma khas durian Bali.'),
            self::Alpukat => __('Alpukat kami dipetik dari pohon saat sudah tua dan dibiarkan matang alami. Daging buahnya tebal, lembut, dan gurih — pas untuk jus, salad, atau roti panggang.'),
            self::Manggis => __('Manggis dijuluki "ratu buah". Daging buahnya putih bersih dengan rasa manis-asam yang menyegarkan. Kami memilih manggis dengan kulit mulus dan buah yang padat.'),
        };
    }
}
