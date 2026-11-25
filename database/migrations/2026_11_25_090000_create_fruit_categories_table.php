<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations. Adds the categories that used to be fixed in the code; products refer to them by slug.
     */
    public function up(): void
    {
        Schema::create('fruit_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('slug', 20)->unique();
            $table->string('emoji', 16);
            $table->string('tagline', 100);
            $table->string('intro', 500);
            $table->string('image_path')->nullable();
            $table->string('meta_title', 70)->nullable();
            $table->string('meta_description', 170)->nullable();
            $table->json('translations')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        foreach ($this->categories() as $index => $category) {
            DB::table('fruit_categories')->insert([
                ...$category,
                'translations' => json_encode($category['translations'], JSON_UNESCAPED_UNICODE),
                'sort_order' => $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations. Categories added in the admin panel are lost.
     */
    public function down(): void
    {
        Schema::dropIfExists('fruit_categories');
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function categories(): array
    {
        return [
            [
                'name' => 'Durian',
                'slug' => 'durian',
                'emoji' => '🍈',
                'tagline' => 'Matang pohon, manis legit, aroma kuat',
                'intro' => 'Durian dari kebun kami dipanen saat matang alami di pohon, bukan diperam. Hasilnya daging buah yang tebal, manis legit, dengan aroma khas durian Bali.',
                'meta_title' => 'Jual Durian Bali Asli Matang Pohon – Harga Terbaru',
                'meta_description' => 'Beli durian asli Bali dari Kebun Sangkara: durian lokal & Montong matang pohon, garansi ganti jika busuk. Kirim ke seluruh Indonesia, pesan via WhatsApp.',
                'translations' => ['en' => [
                    'name' => 'Durian',
                    'tagline' => 'Tree-ripened, rich and sweet, bold aroma',
                    'intro' => 'Our durian is harvested when it ripens naturally on the tree, never force-ripened. The result is thick, rich, sweet flesh with the signature aroma of Bali durian.',
                    'meta_title' => 'Buy Tree-Ripened Bali Durian – Latest Prices',
                    'meta_description' => 'Buy real Bali durian from Kebun Sangkara: tree-ripened local & Montong durian, replaced if rotten. Shipping across Indonesia, order via WhatsApp.',
                ]],
            ],
            [
                'name' => 'Alpukat',
                'slug' => 'alpukat',
                'emoji' => '🥑',
                'tagline' => 'Daging tebal, lembut seperti mentega',
                'intro' => 'Alpukat kami dipetik dari pohon saat sudah tua dan dibiarkan matang alami. Daging buahnya tebal, lembut, dan gurih — pas untuk jus, salad, atau roti panggang.',
                'meta_title' => 'Jual Alpukat Mentega Bali Segar – Harga Terbaru',
                'meta_description' => 'Beli alpukat mentega segar dari Kebun Sangkara Bali. Daging tebal, pulen, cocok untuk jus & salad. Kirim ke seluruh Indonesia, pesan via WhatsApp.',
                'translations' => ['en' => [
                    'name' => 'Avocado',
                    'tagline' => 'Thick flesh, smooth as butter',
                    'intro' => 'Our avocados are picked mature and left to ripen naturally. The flesh is thick, smooth and savory — perfect for juice, salads or toast.',
                    'meta_title' => 'Buy Fresh Bali Butter Avocado – Latest Prices',
                    'meta_description' => 'Buy fresh butter avocado from Kebun Sangkara Bali. Thick, creamy flesh for juice & salads. Shipping across Indonesia, order via WhatsApp.',
                ]],
            ],
            [
                'name' => 'Manggis',
                'slug' => 'manggis',
                'emoji' => '🟣',
                'tagline' => 'Ratu buah, manis-asam menyegarkan',
                'intro' => 'Manggis dijuluki "ratu buah". Daging buahnya putih bersih dengan rasa manis-asam yang menyegarkan. Kami memilih manggis dengan kulit mulus dan buah yang padat.',
                'meta_title' => 'Jual Manggis Bali Segar dari Kebun – Harga Terbaru',
                'meta_description' => 'Beli manggis segar dari Kebun Sangkara Bali. Kulit ungu mulus, daging putih manis-asam. Kirim ke seluruh Indonesia, pesan via WhatsApp.',
                'translations' => ['en' => [
                    'name' => 'Mangosteen',
                    'tagline' => 'Queen of fruits, refreshingly sweet and tangy',
                    'intro' => 'Mangosteen is known as the "queen of fruits". Its pure white flesh is refreshingly sweet and tangy. We select mangosteens with smooth skin and firm fruit.',
                    'meta_title' => 'Buy Fresh Bali Mangosteen – Latest Prices',
                    'meta_description' => 'Buy fresh mangosteen from Kebun Sangkara Bali. Smooth purple skin, sweet-tangy white flesh. Shipping across Indonesia, order via WhatsApp.',
                ]],
            ],
        ];
    }
};
