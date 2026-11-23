<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Faq;
use App\Models\Product;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Starter content for the durian shop. Prices are examples and must be updated from the admin panel.
 */
class ContentSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->seedProducts();
        $this->seedFaqs();
        $this->seedTestimonials();
        $this->seedArticles();
    }

    private function seedProducts(): void
    {
        $products = [
            [
                'name' => 'Durian Lokal Bali Pupuan',
                'slug' => 'durian-lokal-bali-pupuan',
                'variety' => 'Durian lokal Bali',
                'origin' => 'Pupuan, Tabanan',
                'short_description' => 'Durian lokal asli Bali dari kebun Pupuan, Tabanan. Daging tebal, manis legit dengan sedikit rasa pahit dan aroma kuat khas durian kampung.',
                'description' => "Durian lokal Bali dari daerah **Pupuan, Tabanan** adalah durian kampung yang tumbuh di dataran tinggi dengan udara sejuk. Karakter rasanya khas: **manis legit, sedikit pahit**, dengan aroma yang kuat — favorit para pecinta durian sejati.\n\n## Keunggulan\n\n- Dipanen matang pohon, bukan diperam\n- Dipilih satu per satu sebelum dikirim\n- Garansi ganti jika busuk atau mentah\n- Bisa minta dibukakan sebelum diantar\n\n## Ukuran & Harga\n\nHarga per buah tergantung ukuran (umumnya 1,5–3 kg). Silakan tanyakan stok dan ukuran yang tersedia hari ini via WhatsApp.\n\n## Pengiriman\n\nKami mengantar ke Denpasar, Badung, Gianyar, Tabanan dan area lain di Bali. Durian dikemas rapi agar aroma tidak mengganggu selama perjalanan.",
                'price' => 75000,
                'price_unit' => 'per buah',
                'is_featured' => true,
                'sort_order' => 1,
                'meta_title' => 'Durian Lokal Bali Pupuan Asli – Matang Pohon, Antar se-Bali',
            ],
            [
                'name' => 'Durian Montong Bali',
                'slug' => 'durian-montong-bali',
                'variety' => 'Montong',
                'origin' => 'Tabanan, Bali',
                'short_description' => 'Durian Montong yang ditanam di kebun Bali. Daging tebal berwarna kuning, biji kecil, rasa manis lembut dan aroma tidak terlalu menyengat.',
                'description' => "Durian **Montong Bali** cocok untuk Anda yang suka durian dengan **daging tebal, biji kecil, dan rasa manis lembut**. Aromanya lebih ringan dibanding durian lokal, sehingga cocok juga untuk pemula atau tamu dari luar negeri.\n\n## Kenapa Montong?\n\n- Daging buah tebal dan creamy\n- Biji relatif kecil\n- Rasa manis, tidak pahit\n\n## Cara Pesan\n\nKlik tombol **Pesan via WhatsApp**, sebutkan berat yang diinginkan dan alamat pengiriman. Kami akan konfirmasi stok dan ongkos kirim.",
                'price' => 70000,
                'price_unit' => 'per kg',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Durian Kupas Bali (Box 500 gr)',
                'slug' => 'durian-kupas-bali-box',
                'variety' => 'Campuran lokal Bali',
                'origin' => 'Bali',
                'short_description' => 'Daging durian Bali pilihan yang sudah dikupas dan dikemas dalam box 500 gram. Praktis, tanpa repot membuka kulit, siap santap.',
                'description' => "Ingin makan durian tanpa repot membuka kulitnya? **Durian kupas Bali** kami berisi daging durian pilihan yang sudah dipisahkan dari kulit dan dikemas higienis dalam box.\n\n## Cocok Untuk\n\n- Dinikmati di hotel atau villa\n- Oleh-oleh untuk keluarga\n- Bahan pancake durian, es durian, atau kolak\n\n## Penyimpanan\n\nSimpan di kulkas dan habiskan dalam 2–3 hari. Untuk penyimpanan lebih lama, simpan di freezer.",
                'price' => 95000,
                'price_unit' => 'per box',
                'sort_order' => 3,
            ],
            [
                'name' => 'Paket Durian Keluarga',
                'slug' => 'paket-durian-keluarga',
                'variety' => 'Durian lokal Bali',
                'origin' => 'Tabanan & Buleleng',
                'short_description' => 'Paket hemat 3 buah durian lokal Bali pilihan, pas untuk dinikmati bersama keluarga atau acara kumpul-kumpul.',
                'description' => "**Paket Durian Keluarga** berisi 3 buah durian lokal Bali pilihan dengan ukuran sedang. Lebih hemat dibanding beli satuan, dan semua tetap mendapat **garansi ganti jika busuk**.\n\nIngin jumlah lebih banyak untuk acara atau usaha? Hubungi kami via WhatsApp untuk harga khusus.",
                'price' => 200000,
                'price_unit' => 'per paket',
                'sort_order' => 4,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['slug' => $product['slug']], $product);
        }
    }

    private function seedFaqs(): void
    {
        $faqs = [
            ['Apakah durian yang dijual benar-benar durian asli Bali?', 'Ya. Durian kami berasal dari kebun petani lokal di Bali, terutama daerah Tabanan (Pupuan, Penebel) dan Buleleng. Kami tidak menjual durian dari luar pulau sebagai durian Bali.'],
            ['Kapan musim durian di Bali?', 'Musim durian di Bali umumnya berlangsung sekitar akhir tahun hingga awal tahun, kurang lebih November sampai Maret, tergantung cuaca dan daerah. Di luar musim, stok terbatas sehingga sebaiknya tanyakan dulu via WhatsApp.'],
            ['Bagaimana cara memesan durian?', 'Klik tombol "Pesan via WhatsApp" di website ini, sebutkan jenis durian, jumlah, dan alamat pengiriman. Kami akan mengonfirmasi stok, total harga, dan jadwal pengiriman.'],
            ['Apakah bisa diantar ke hotel atau villa?', 'Bisa. Kami mengantar ke rumah, hotel, maupun villa di Denpasar, Kuta, Seminyak, Canggu, Ubud, Nusa Dua dan area lain di Bali. Beberapa hotel melarang durian dibawa ke kamar, jadi pastikan dulu aturan hotel Anda.'],
            ['Bagaimana jika durian yang diterima busuk atau mentah?', 'Kami memberikan garansi ganti. Kirimkan foto durian melalui WhatsApp maksimal beberapa jam setelah diterima, dan kami akan mengganti dengan durian lain.'],
            ['Metode pembayaran apa saja yang tersedia?', 'Pembayaran bisa melalui transfer bank, QRIS, atau tunai saat durian diantar (COD) untuk area tertentu. Detailnya akan diinformasikan saat pemesanan via WhatsApp.'],
        ];

        foreach ($faqs as $index => [$question, $answer]) {
            Faq::updateOrCreate(['question' => $question], ['answer' => $answer, 'sort_order' => $index + 1, 'is_published' => true]);
        }
    }

    /**
     * Example testimonials are NOT published: replace them with real customer reviews before showing them.
     */
    private function seedTestimonials(): void
    {
        $testimonials = [
            ['name' => 'Contoh: Putu', 'location' => 'Denpasar', 'content' => 'Contoh testimoni — ganti dengan ulasan asli dari pelanggan Anda sebelum ditampilkan.'],
            ['name' => 'Contoh: Sarah', 'location' => 'Canggu', 'content' => 'Contoh testimoni — ganti dengan ulasan asli dari pelanggan Anda sebelum ditampilkan.'],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::updateOrCreate(['name' => $testimonial['name']], [...$testimonial, 'rating' => 5, 'is_published' => false]);
        }
    }

    private function seedArticles(): void
    {
        $author = User::query()->orderBy('id')->first();

        $articles = [
            [
                'title' => 'Cara Memilih Durian yang Matang dan Manis: 7 Tips Ampuh',
                'slug' => 'cara-memilih-durian-matang-dan-manis',
                'excerpt' => 'Takut salah pilih durian? Ikuti 7 tips memilih durian yang matang, manis, dan tidak busuk — dari aroma, bunyi, tangkai, hingga bentuk duri.',
                'body' => <<<'MD'
Membeli durian memang gampang-gampang susah. Dari luar semua terlihat sama, tetapi isinya bisa sangat berbeda: ada yang manis legit, ada yang masih mentah, bahkan ada yang sudah busuk. Berikut tips memilih durian yang matang dan manis yang biasa dipakai para petani durian di Bali.

## 1. Cium Aromanya

Durian matang memiliki **aroma yang kuat dan harum**, terutama di bagian pangkal (dekat tangkai) dan celah-celah kulitnya. Jika aromanya lemah, kemungkinan durian belum matang. Sebaliknya, aroma yang terlalu menyengat seperti alkohol bisa menandakan durian terlalu matang.

## 2. Perhatikan Tangkainya

Tangkai durian yang masih segar biasanya berwarna hijau kecokelatan dan tidak kering. Tangkai yang sudah sangat kering dan hitam menandakan durian sudah lama dipetik.

## 3. Tekan Ujung Duri

Tekan dua duri yang berdekatan, lalu rapatkan. Pada durian matang, **duri terasa lebih lentur** dan mudah dirapatkan. Durian mentah memiliki duri yang keras dan kaku.

## 4. Ketuk dan Dengarkan Bunyinya

Ketuk kulit durian perlahan dengan gagang pisau atau kayu. Bunyi yang **"bergema" atau terdengar kopong** biasanya menandakan daging durian sudah lunak dan matang.

## 5. Goyangkan Buahnya

Pada beberapa jenis durian, daging buah yang sudah matang akan sedikit terlepas dari kulit sehingga terasa ada gerakan ketika digoyang.

## 6. Pilih Durian Jatuhan atau Matang Pohon

Durian **matang pohon** (dipanen saat sudah matang alami) rasanya jauh lebih nikmat dibanding durian yang dipetik muda lalu diperam. Itulah kenapa kami di Bali hanya menjual durian matang pohon.

## 7. Beli dari Penjual Terpercaya

Cara paling aman adalah membeli dari penjual yang memberikan **garansi ganti** jika durian busuk atau mentah. Dengan begitu Anda tidak perlu khawatir rugi.

## Kesimpulan

Gunakan indra penciuman, perabaan, dan pendengaran untuk memilih durian. Jika tidak ingin repot, Anda bisa langsung [memesan durian Bali asli](/durian) dari kami — setiap buah sudah kami pilihkan dan bergaransi.
MD,
            ],
            [
                'title' => 'Musim Durian di Bali: Kapan Waktu Terbaik Berburu Durian?',
                'slug' => 'musim-durian-di-bali',
                'excerpt' => 'Kapan musim durian di Bali? Simak perkiraan bulan panen durian Bali, daerah penghasil durian terbaik, dan tips mendapatkan durian segar.',
                'body' => <<<'MD'
Bagi pecinta durian, mengetahui **kapan musim durian di Bali** sangat penting agar bisa menikmati durian segar dengan harga terbaik. Berikut panduan singkatnya.

## Perkiraan Musim Durian di Bali

Secara umum, musim durian di Bali berlangsung sekitar **akhir tahun hingga awal tahun berikutnya** — kurang lebih **November sampai Maret**. Puncak panen biasanya terjadi di sekitar Desember hingga Februari. Namun, waktu pastinya bisa bergeser tergantung curah hujan dan ketinggian kebun.

## Daerah Penghasil Durian di Bali

Beberapa daerah di Bali dikenal sebagai sentra durian:

- **Tabanan** – terutama Pupuan dan Penebel, dengan kebun-kebun di dataran tinggi yang sejuk.
- **Buleleng** – seperti Munduk dan sekitarnya.
- **Jembrana** – di bagian barat Bali.

Durian dari setiap daerah memiliki karakter rasa yang sedikit berbeda, dari manis legit hingga manis dengan sentuhan pahit.

## Kenapa Harga Durian Naik Turun?

Di awal dan akhir musim, jumlah durian masih sedikit sehingga harganya cenderung lebih tinggi. Saat puncak panen, stok melimpah dan harga biasanya lebih bersahabat.

## Tips Mendapatkan Durian Segar

1. Pesan langsung dari penjual yang bekerja sama dengan petani.
2. Tanyakan apakah durian **matang pohon** atau diperam.
3. Pastikan ada garansi jika durian busuk.

Ingin menikmati durian Bali tanpa harus pergi ke kebun? [Lihat harga durian Bali hari ini](/durian) dan pesan langsung via WhatsApp.
MD,
            ],
            [
                'title' => 'Jenis-Jenis Durian yang Bisa Anda Nikmati di Bali',
                'slug' => 'jenis-jenis-durian-di-bali',
                'excerpt' => 'Mengenal jenis durian di Bali: durian lokal Bali, Montong, hingga durian kupas. Mana yang paling cocok untuk Anda?',
                'body' => <<<'MD'
Bali menawarkan beragam pilihan durian, dari durian kampung asli hingga varietas unggul yang kini ditanam di kebun-kebun lokal. Berikut beberapa jenis durian yang populer.

## Durian Lokal Bali

Durian lokal atau durian kampung Bali tumbuh di kebun-kebun tradisional di Tabanan, Buleleng, dan Jembrana. Ciri khasnya:

- Rasa **manis legit dengan sedikit pahit**
- Aroma kuat
- Ukuran buah bervariasi

Durian ini sangat digemari pecinta durian "sejati" yang menyukai rasa kompleks.

## Durian Montong

Montong adalah varietas asal Thailand yang kini juga dibudidayakan di Indonesia, termasuk di Bali. Keunggulannya:

- Daging tebal dan lembut
- Biji kecil
- Rasa manis dengan aroma lebih ringan

Cocok untuk pemula atau wisatawan yang baru pertama kali mencoba durian.

## Durian Kupas

Bagi yang tidak mau repot membuka kulit durian, **durian kupas** adalah pilihan praktis. Daging durian sudah dipisahkan dan dikemas dalam box, siap disantap kapan saja.

## Mana yang Paling Cocok?

| Selera | Rekomendasi |
| --- | --- |
| Suka rasa kuat & pahit-manis | Durian lokal Bali |
| Suka manis lembut, aroma ringan | Durian Montong |
| Ingin praktis | Durian kupas |

Masih bingung? Konsultasikan selera Anda dengan kami via WhatsApp, dan kami akan merekomendasikan [durian Bali terbaik](/durian) yang sedang tersedia.
MD,
            ],
        ];

        foreach ($articles as $index => $article) {
            Article::updateOrCreate(['slug' => $article['slug']], [
                ...$article,
                'user_id' => $author?->id,
                'is_published' => true,
                'published_at' => now()->subDays(($index + 1) * 3),
            ]);
        }
    }
}
