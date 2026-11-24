<?php

namespace Database\Seeders;

use App\Enums\FruitCategory;
use App\Enums\ProductType;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Starter fruit products. Prices are examples and must be updated from the admin panel.
 */
class HasilPerkebunanSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $products = [
            [
                'name' => 'Durian Lokal Bali',
                'slug' => 'durian-lokal-bali-pupuan',
                'category' => FruitCategory::Durian,
                'variety' => 'Durian lokal Bali',
                'origin' => 'Kebun Sangkara',
                'short_description' => 'Durian lokal Bali dari kebun kami. Daging tebal, manis legit dengan sedikit rasa pahit dan aroma kuat khas durian kampung.',
                'description' => "Durian lokal Bali dari **Kebun Sangkara** dipanen saat matang alami di pohon. Karakter rasanya khas: **manis legit, sedikit pahit**, dengan aroma yang kuat — favorit para pecinta durian sejati.\n\n## Keunggulan\n\n- Matang pohon, bukan diperam\n- Dipilih satu per satu sebelum dikirim\n- Garansi ganti jika busuk atau mentah\n- Bisa minta dibukakan sebelum diantar\n\n## Ukuran & Harga\n\nHarga per buah tergantung ukuran (umumnya 1,5–3 kg). Tanyakan stok dan ukuran yang tersedia hari ini via WhatsApp.",
                'price' => 75000,
                'price_unit' => 'per buah',
                'is_featured' => true,
                'sort_order' => 1,
                'meta_title' => 'Durian Lokal Bali Matang Pohon – Kebun Sangkara',
            ],
            [
                'name' => 'Durian Montong Bali',
                'slug' => 'durian-montong-bali',
                'category' => FruitCategory::Durian,
                'variety' => 'Montong',
                'origin' => 'Kebun Sangkara',
                'short_description' => 'Durian Montong dari kebun Bali. Daging tebal berwarna kuning, biji kecil, rasa manis lembut dan aroma tidak terlalu menyengat.',
                'description' => "Durian **Montong** cocok untuk Anda yang suka durian dengan **daging tebal, biji kecil, dan rasa manis lembut**. Aromanya lebih ringan dibanding durian lokal, sehingga cocok juga untuk pemula atau tamu dari luar negeri.\n\n## Cara Pesan\n\nKlik tombol **Pesan via WhatsApp**, sebutkan berat yang diinginkan dan alamat pengiriman.",
                'price' => 70000,
                'price_unit' => 'per kg',
                'sort_order' => 2,
            ],
            [
                'name' => 'Alpukat Mentega',
                'slug' => 'alpukat-mentega-bali',
                'category' => FruitCategory::Alpukat,
                'variety' => 'Mentega',
                'origin' => 'Kebun Sangkara',
                'short_description' => 'Alpukat mentega dengan daging tebal berwarna kuning, tekstur lembut dan pulen seperti mentega. Pas untuk jus, salad, dan roti panggang.',
                'description' => "**Alpukat mentega** dari kebun kami dipetik saat buah sudah tua, lalu dibiarkan matang alami. Dagingnya **tebal, pulen, dan gurih** dengan biji yang relatif kecil.\n\n## Cocok Untuk\n\n- Jus alpukat dan smoothie\n- Salad, guacamole, dan roti panggang\n- Kebutuhan kafe dan restoran\n\n## Tips Penyimpanan\n\nSimpan di suhu ruang hingga empuk saat ditekan, lalu pindahkan ke kulkas agar tahan lebih lama.",
                'price' => 35000,
                'price_unit' => 'per kg',
                'is_featured' => true,
                'sort_order' => 3,
                'meta_title' => 'Alpukat Mentega Bali Segar – Kebun Sangkara',
            ],
            [
                'name' => 'Manggis Bali',
                'slug' => 'manggis-bali',
                'category' => FruitCategory::Manggis,
                'variety' => 'Manggis lokal',
                'origin' => 'Kebun Sangkara',
                'short_description' => 'Manggis segar dengan kulit ungu mulus dan daging buah putih bersih. Rasa manis-asam yang menyegarkan, sang "ratu buah".',
                'description' => "**Manggis** dijuluki *ratu buah* karena rasanya yang istimewa. Manggis dari kebun kami dipilih yang **kulitnya mulus, buahnya padat**, dan daging buahnya putih bersih.\n\n## Cara Memilih Manggis yang Bagus\n\n- Kulit berwarna ungu tua dan tidak keras\n- Kelopak (tangkai) masih hijau segar\n- Jumlah kelopak di pantat buah menunjukkan jumlah juring di dalamnya\n\nMusim manggis terbatas, jadi pesan selagi stok tersedia.",
                'price' => 30000,
                'price_unit' => 'per kg',
                'sort_order' => 5,
            ],
            [
                'name' => 'Paket Buah Kebun',
                'slug' => 'paket-buah-kebun',
                'category' => FruitCategory::Durian,
                'variety' => 'Campuran',
                'origin' => 'Kebun Sangkara',
                'short_description' => 'Paket campuran 1 durian lokal, 1 kg alpukat, dan 1 kg manggis. Pas untuk keluarga atau hantaran.',
                'description' => "**Paket Buah Kebun** berisi pilihan terbaik dari kebun kami: 1 buah durian lokal, 1 kg alpukat mentega, dan 1 kg manggis (menyesuaikan musim panen).\n\nCocok untuk hantaran, oleh-oleh, atau stok buah keluarga selama seminggu.",
                'price' => 175000,
                'price_unit' => 'per paket',
                'sort_order' => 6,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['slug' => $product['slug']], [...$product, 'type' => ProductType::Buah]);
        }
    }
}
