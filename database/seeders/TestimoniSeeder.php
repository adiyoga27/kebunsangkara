<?php

namespace Database\Seeders;

use App\Enums\Locale;
use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Example testimonials, labelled "Contoh" in their name. They are visible while developing but stay drafts in
 * production: replace them with real customer reviews from the admin panel before publishing them there.
 */
class TestimoniSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Contoh: Putu',
                'location' => 'Denpasar',
                'product' => 'durian-lokal-bali-pupuan',
                'rating' => 5,
                'content' => 'Duriannya matang pohon, dagingnya tebal dan manis legit. Pesan pagi lewat WhatsApp, sorenya sudah diantar.',
                'english' => 'The durian was tree-ripened, with thick, rich and sweet flesh. I ordered via WhatsApp in the morning and it arrived that afternoon.',
            ],
            [
                'name' => 'Contoh: Sarah',
                'location' => 'Canggu',
                'product' => 'alpukat-mentega-bali',
                'rating' => 5,
                'content' => 'Alpukatnya lembut dan matangnya merata. Cocok untuk menu sarapan di vila kami.',
                'english' => 'The avocados were smooth and evenly ripe. Perfect for the breakfast menu at our villa.',
            ],
            [
                'name' => 'Contoh: Wayan',
                'location' => 'Gianyar',
                'product' => 'manggis-bali',
                'rating' => 4,
                'content' => 'Manggisnya segar dan manis. Ada satu buah yang keras, tetapi langsung diganti.',
                'english' => 'The mangosteens were fresh and sweet. One of them was hard, but it was replaced right away.',
            ],
            [
                'name' => 'Contoh: Komang',
                'location' => 'Tabanan',
                'product' => 'bibit-durian-montong-okulasi',
                'rating' => 5,
                'content' => 'Bibitnya sehat dan dikemas rapi. Cara tanamnya dijelaskan dengan sabar lewat WhatsApp.',
                'english' => 'The seedlings were healthy and neatly packed. They patiently explained how to plant them via WhatsApp.',
            ],
            [
                'name' => 'Contoh: Ketut',
                'location' => 'Badung',
                'product' => 'pupuk-kompos-organik',
                'rating' => 5,
                'content' => 'Komposnya gembur dan tidak berbau menyengat. Praktis untuk campuran media tanam di rumah.',
                'english' => 'The compost is loose and has no strong smell. Handy for potting mixes at home.',
            ],
            [
                'name' => 'Contoh: Dewi',
                'location' => 'Ubud',
                'product' => 'madu-murni',
                'rating' => 4,
                'content' => 'Madunya enak untuk campuran teh dan olesan roti. Botolnya dikemas aman.',
                'english' => 'The honey is lovely in tea and on toast. The bottle was packed safely.',
            ],
        ];

        $productIds = Product::query()->whereIn('slug', array_column($testimonials, 'product'))->pluck('id', 'slug');

        foreach ($testimonials as $testimonial) {
            Testimonial::firstOrCreate(['name' => $testimonial['name']], [
                'location' => $testimonial['location'],
                'product_id' => $productIds[$testimonial['product']] ?? null,
                'rating' => $testimonial['rating'],
                'content' => $testimonial['content'],
                'translations' => [Locale::English->value => ['content' => $testimonial['english']]],
                'is_published' => ! app()->isProduction(),
            ]);
        }
    }
}
