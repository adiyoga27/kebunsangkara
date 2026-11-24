<?php

namespace Database\Seeders;

use App\Enums\Locale;
use App\Enums\ProductType;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Example fertilizers: replace names, descriptions and prices with the real products from the admin panel.
 * Products that already exist, also in the trash, are left untouched.
 */
class PupukSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $fertilizers = [
            [
                'name' => 'Pupuk Kompos Organik',
                'slug' => 'pupuk-kompos-organik',
                'variety' => 'Pupuk organik padat',
                'short_description' => 'Pupuk kompos dari bahan organik untuk campuran media tanam dan pemupukan dasar tanaman buah.',
                'description' => "**Pupuk kompos organik** berasal dari bahan organik yang sudah melalui proses pengomposan. Cocok dipakai sebagai campuran media tanam maupun pupuk dasar.\n\n## Cara Pakai\n\n- Campurkan dengan tanah saat menyiapkan lubang tanam\n- Taburkan di sekitar tajuk tanaman, lalu tutup tipis dengan tanah\n- Siram setelah pemupukan\n\nTanyakan stok, harga terbaru, dan ongkos kirim melalui WhatsApp.",
                'price' => 30000,
                'price_unit' => 'per karung',
                'sort_order' => 21,
            ],
            [
                'name' => 'Pupuk Kandang Fermentasi',
                'slug' => 'pupuk-kandang-fermentasi',
                'variety' => 'Pupuk organik padat',
                'short_description' => 'Pupuk kandang yang sudah difermentasi, praktis untuk pupuk dasar kebun dan tanaman pot.',
                'description' => "**Pupuk kandang fermentasi** adalah pupuk dari kotoran ternak yang sudah difermentasi sebelum dipakai.\n\n## Cara Pakai\n\n- Campurkan dengan tanah sebagai pupuk dasar\n- Beri jarak dari pangkal batang saat pemupukan susulan\n- Sesuaikan jumlahnya dengan ukuran tanaman\n\nTanyakan stok, harga terbaru, dan ongkos kirim melalui WhatsApp.",
                'price' => 25000,
                'price_unit' => 'per karung',
                'sort_order' => 22,
            ],
            [
                'name' => 'Pupuk Organik Cair',
                'slug' => 'pupuk-organik-cair',
                'variety' => 'Pupuk organik cair',
                'short_description' => 'Pupuk organik cair untuk pemupukan susulan. Diencerkan dengan air sebelum disiramkan ke tanaman.',
                'description' => "**Pupuk organik cair** praktis untuk pemupukan susulan pada bibit maupun tanaman buah.\n\n## Cara Pakai\n\n- Encerkan sesuai takaran pada kemasan\n- Siramkan ke tanah di sekitar perakaran\n- Lakukan pada pagi atau sore hari\n\nTanyakan stok, harga terbaru, dan ongkos kirim melalui WhatsApp.",
                'price' => 40000,
                'price_unit' => 'per liter',
                'sort_order' => 23,
            ],
        ];

        foreach ($fertilizers as $fertilizer) {
            Product::withTrashed()->firstOrCreate(['slug' => $fertilizer['slug']], [
                ...$fertilizer,
                'type' => ProductType::Pupuk,
                'category' => null,
                'translations' => array_filter([Locale::English->value => EnglishTranslationSeeder::products()[$fertilizer['slug']] ?? null]),
            ]);
        }
    }
}
