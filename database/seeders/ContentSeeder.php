<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Faq;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Starter content for Kebun Sangkara. Prices are examples and must be updated from the admin panel.
 */
class ContentSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            HasilPerkebunanSeeder::class,
            BibitSeeder::class,
            PupukSeeder::class,
            ProdukKesehatanSeeder::class,
        ]);

        $this->seedFaqs();
        $this->seedTestimonials();
        $this->seedArticles();
    }

    private function seedFaqs(): void
    {
        $faqs = [
            ['Apakah buahnya benar-benar dari kebun di Bali?', 'Ya. Durian, alpukat, dan manggis kami berasal dari kebun Kebun Sangkara di Bali dan dipetik saat matang. Kami tidak menjual buah impor atau buah dari luar pulau.'],
            ['Kapan musim durian dan manggis di Bali?', 'Musim durian di Bali umumnya sekitar akhir tahun hingga awal tahun (kurang lebih November–Maret), dan musim manggis biasanya berdekatan. Alpukat lebih sering tersedia sepanjang tahun. Waktu pastinya mengikuti cuaca, jadi tanyakan stok via WhatsApp.'],
            ['Bagaimana cara memesan buah?', 'Klik tombol "Pesan via WhatsApp" di website ini, sebutkan jenis buah, jumlah, dan alamat pengiriman. Kami akan mengonfirmasi stok, total harga, dan jadwal pengiriman.'],
            ['Apakah bisa diantar ke hotel atau villa?', 'Bisa. Kami mengantar ke rumah, hotel, maupun villa di seluruh Bali. Untuk durian, pastikan dulu aturan hotel karena beberapa hotel melarang durian dibawa ke kamar.'],
            ['Bagaimana jika buah yang diterima busuk atau rusak?', 'Kami memberikan garansi ganti. Kirimkan foto buah melalui WhatsApp maksimal beberapa jam setelah diterima, dan kami akan menggantinya.'],
            ['Apakah melayani pesanan dalam jumlah besar?', 'Ya. Kami melayani pesanan untuk kafe, restoran, hotel, usaha jus, maupun acara. Hubungi kami via WhatsApp untuk harga khusus.'],
            ['Apa bedanya bibit okulasi, sambung pucuk, dan bibit dari biji?', 'Bibit okulasi dan sambung pucuk diperbanyak dari pohon induk yang sudah terbukti berbuah, sehingga sifatnya sama dengan induknya dan umumnya lebih cepat berbuah. Bibit dari biji butuh waktu lebih lama dan sifat buahnya belum tentu sama dengan induknya.'],
            ['Apakah bibit bisa dikirim, dan apakah ada panduan menanam?', 'Bisa. Bibit dikemas dalam polybag agar aman selama perjalanan. Setiap pembelian bibit mendapat konsultasi gratis cara tanam dan perawatan melalui WhatsApp.'],
            ['Metode pembayaran apa saja yang tersedia?', 'Pembayaran bisa melalui transfer bank, QRIS, atau tunai saat buah diantar (COD) untuk area tertentu. Detailnya diinformasikan saat pemesanan via WhatsApp.'],
        ];

        Faq::query()->whereNotIn('question', array_column($faqs, 0))->delete();

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

Durian **matang pohon** (dipanen saat sudah matang alami) rasanya jauh lebih nikmat dibanding durian yang dipetik muda lalu diperam. Itulah kenapa di Kebun Sangkara kami hanya menjual durian matang pohon.

## 7. Beli dari Penjual Terpercaya

Cara paling aman adalah membeli dari penjual yang memberikan **garansi ganti** jika durian busuk atau mentah. Dengan begitu Anda tidak perlu khawatir rugi.

## Kesimpulan

Gunakan indra penciuman, perabaan, dan pendengaran untuk memilih durian. Jika tidak ingin repot, Anda bisa langsung [memesan durian dari kebun kami](/buah/durian) dari kami — setiap buah sudah kami pilihkan dan bergaransi.
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

Ingin menikmati durian Bali tanpa harus pergi ke kebun? [Lihat harga durian hari ini](/buah/durian) dan pesan langsung via WhatsApp.
MD,
            ],
            [
                'title' => 'Cara Memilih Alpukat dan Manggis yang Matang dan Manis',
                'slug' => 'cara-memilih-alpukat-manggis',
                'excerpt' => 'Panduan praktis memilih alpukat yang pulen dan manggis yang segar — supaya tidak salah beli buah.',
                'body' => <<<'MD'
Selain durian, Kebun Sangkara juga memanen alpukat dan manggis. Setiap buah punya ciri matang yang berbeda. Berikut panduan singkatnya.

## Cara Memilih Alpukat

- **Tekan perlahan** bagian dekat tangkai. Alpukat matang terasa sedikit empuk, tetapi tidak lembek.
- **Cek tangkainya.** Jika tangkai mudah dicabut dan bagian bawahnya berwarna hijau kekuningan, alpukat siap dimakan.
- Alpukat yang masih keras bisa disimpan di suhu ruang 2–4 hari sampai matang.

## Cara Memilih Manggis

- **Kulit ungu tua** dan sedikit lentur saat ditekan menandakan manggis segar. Kulit yang sangat keras biasanya sudah lama dipetik.
- **Kelopak hijau segar** di bagian atas menandakan buah baru dipanen.
- Hitung **kelopak kecil di bagian bawah** buah — jumlahnya sama dengan jumlah juring daging di dalamnya.

## Kesimpulan

Buah yang dipetik langsung dari kebun selalu lebih segar. Lihat [daftar harga buah kami](/produk) dan pesan langsung via WhatsApp.
MD,
            ],
            [
                'title' => 'Cara Menanam Bibit Durian Okulasi agar Tumbuh Subur',
                'slug' => 'cara-menanam-bibit-durian-okulasi',
                'excerpt' => 'Panduan langkah demi langkah menanam bibit durian okulasi: memilih lokasi, menyiapkan lubang tanam, pemupukan, hingga perawatan awal.',
                'body' => <<<'MD'
Menanam durian sendiri kini semakin diminati. Dengan **bibit okulasi** yang berasal dari pohon induk unggul, pohon durian umumnya bisa mulai berbuah lebih cepat dibanding bibit dari biji. Berikut langkah-langkah menanamnya.

## 1. Pilih Lokasi yang Tepat

- Durian menyukai **sinar matahari penuh** dan tanah yang **gembur serta tidak tergenang air**.
- Beri jarak tanam yang cukup lebar, umumnya **8–10 meter** antar pohon, karena tajuk durian akan tumbuh besar.

## 2. Siapkan Lubang Tanam

1. Gali lubang sekitar **60 × 60 × 60 cm**.
2. Pisahkan tanah bagian atas dan bawah.
3. Campurkan tanah bagian atas dengan **pupuk kandang matang**, lalu diamkan beberapa hari sebelum ditanami.

## 3. Menanam Bibit

1. Sobek polybag dengan hati-hati agar **akar dan tanah tidak pecah**.
2. Letakkan bibit di tengah lubang, pastikan **sambungan okulasi berada di atas permukaan tanah**.
3. Timbun dengan campuran tanah dan pupuk, padatkan perlahan, lalu siram.
4. Pasang ajir (tongkat penyangga) agar bibit tidak goyah tertiup angin.

## 4. Perawatan Awal

- **Siram rutin** terutama di musim kemarau, tetapi jangan sampai tergenang.
- Beri **naungan sementara** pada minggu-minggu awal jika matahari sangat terik.
- **Buang tunas liar** yang tumbuh di bawah sambungan okulasi.
- Berikan pupuk secara berkala sesuai umur tanaman.

## Butuh Bibit atau Bantuan?

Kebun Sangkara menyediakan [bibit durian, alpukat, dan manggis](/bibit) dari pohon induk di kebun kami sendiri — lengkap dengan konsultasi tanam gratis via WhatsApp.
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

Masih bingung? Konsultasikan selera Anda dengan kami via WhatsApp, dan kami akan merekomendasikan [durian terbaik](/buah/durian) yang sedang tersedia.
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
