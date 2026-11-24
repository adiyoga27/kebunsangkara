<?php

namespace Database\Seeders;

use App\Enums\FruitCategory;
use App\Enums\ProductType;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Starter seedling products. Prices are examples and must be updated from the admin panel.
 */
class BibitSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $seedlings = [
            [
                'name' => 'Bibit Durian Montong Okulasi',
                'slug' => 'bibit-durian-montong-okulasi',
                'category' => FruitCategory::Durian,
                'variety' => 'Montong',
                'propagation_method' => 'Okulasi',
                'plant_height' => '60–80 cm',
                'short_description' => 'Bibit durian Montong hasil okulasi dari pohon induk di kebun kami. Batang sehat, akar kuat, siap tanam.',
                'description' => 'Bibit **durian Montong okulasi** diperbanyak dari pohon induk yang sudah terbukti berbuah di kebun kami. Bibit okulasi umumnya **lebih cepat berbuah** dan sifatnya sama dengan pohon induk.

## Spesifikasi

- Metode: okulasi
- Tinggi: sekitar 60–80 cm
- Media: polybag

## Bonus

Konsultasi gratis cara tanam dan perawatan via WhatsApp. Baca juga [panduan menanam bibit durian okulasi](/artikel/cara-menanam-bibit-durian-okulasi).',
                'price' => 85000,
                'sort_order' => 11,
                'is_featured' => true,
            ],
            [
                'name' => 'Bibit Alpukat Mentega Sambung Pucuk',
                'slug' => 'bibit-alpukat-mentega-sambung-pucuk',
                'category' => FruitCategory::Alpukat,
                'variety' => 'Mentega',
                'propagation_method' => 'Sambung pucuk',
                'plant_height' => '50–70 cm',
                'short_description' => 'Bibit alpukat mentega sambung pucuk dari pohon induk berbuah lebat. Cocok untuk pekarangan maupun kebun.',
                'description' => 'Bibit **alpukat mentega sambung pucuk** diambil dari pohon induk dengan buah berdaging tebal dan pulen. Bibit sambung pucuk mewarisi sifat unggul pohon induknya.

## Spesifikasi

- Metode: sambung pucuk
- Tinggi: sekitar 50–70 cm
- Media: polybag

Konsultasi gratis cara tanam via WhatsApp.',
                'price' => 65000,
                'sort_order' => 12,
            ],
            [
                'name' => 'Bibit Manggis',
                'slug' => 'bibit-manggis',
                'category' => FruitCategory::Manggis,
                'variety' => 'Manggis lokal',
                'propagation_method' => 'Sambung pucuk',
                'plant_height' => '40–60 cm',
                'short_description' => 'Bibit manggis dari pohon induk di kebun kami. Tanaman berumur panjang yang cocok untuk investasi kebun.',
                'description' => 'Bibit **manggis** dari pohon induk dengan buah manis dan kulit mulus. Manggis adalah tanaman berumur panjang yang cocok sebagai investasi kebun jangka panjang.

## Spesifikasi

- Metode: sambung pucuk
- Tinggi: sekitar 40–60 cm
- Media: polybag

Konsultasi gratis cara tanam via WhatsApp.',
                'price' => 75000,
                'sort_order' => 14,
            ],
        ];

        foreach ($seedlings as $seedling) {
            Product::updateOrCreate(['slug' => $seedling['slug']], [
                ...$seedling,
                'type' => ProductType::Bibit,
                'origin' => 'Kebun Sangkara',
                'price_unit' => 'per batang',
            ]);
        }
    }
}
