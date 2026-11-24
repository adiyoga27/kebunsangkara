<?php

namespace Database\Seeders;

use App\Enums\ProductType;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Example health products: replace names, descriptions and prices with the real products from the admin panel.
 * Descriptions must stay free of medical claims.
 */
class ProdukKesehatanSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $healthProducts = [
            [
                'name' => 'Teh Kulit Manggis',
                'slug' => 'teh-kulit-manggis',
                'variety' => 'Minuman herbal',
                'short_description' => 'Minuman seduh dari kulit manggis kering. Dikemas praktis untuk dinikmati hangat maupun dingin.',
                'description' => "**Teh kulit manggis** dibuat dari kulit buah manggis yang dikeringkan.\n\n## Cara Penyajian\n\n- Seduh dengan air panas selama beberapa menit\n- Tambahkan madu sesuai selera\n- Simpan kemasan di tempat kering dan tertutup rapat\n\n*Produk ini adalah minuman, bukan obat.*\n\nTanyakan stok, harga terbaru, dan ongkos kirim melalui WhatsApp.",
                'price' => 35000,
                'price_unit' => 'per kemasan',
                'sort_order' => 31,
            ],
            [
                'name' => 'Madu Murni',
                'slug' => 'madu-murni',
                'variety' => 'Madu',
                'short_description' => 'Madu murni dalam botol untuk campuran minuman, olesan roti, atau pemanis alami.',
                'description' => "**Madu murni** untuk campuran teh, jus, olesan roti, atau pengganti gula.\n\n## Penyimpanan\n\n- Simpan di suhu ruang dan hindari sinar matahari langsung\n- Gunakan sendok yang kering dan bersih\n- Tidak untuk bayi di bawah usia 1 tahun\n\n*Produk ini adalah bahan pangan, bukan obat.*\n\nTanyakan stok, harga terbaru, dan ongkos kirim melalui WhatsApp.",
                'price' => 90000,
                'price_unit' => 'per botol',
                'sort_order' => 32,
            ],
            [
                'name' => 'Jahe Bubuk',
                'slug' => 'jahe-bubuk',
                'variety' => 'Minuman herbal',
                'short_description' => 'Jahe bubuk untuk minuman hangat dan bumbu masakan. Praktis, tinggal diseduh.',
                'description' => "**Jahe bubuk** praktis untuk minuman hangat maupun bumbu masakan.\n\n## Cara Penyajian\n\n- Larutkan satu sendok teh dalam segelas air panas\n- Tambahkan gula aren atau madu sesuai selera\n\n*Produk ini adalah bahan pangan, bukan obat.*\n\nTanyakan stok, harga terbaru, dan ongkos kirim melalui WhatsApp.",
                'price' => 30000,
                'price_unit' => 'per kemasan',
                'sort_order' => 33,
            ],
        ];

        foreach ($healthProducts as $healthProduct) {
            Product::updateOrCreate(['slug' => $healthProduct['slug']], [
                ...$healthProduct,
                'type' => ProductType::Kesehatan,
                'category' => null,
            ]);
        }
    }
}
