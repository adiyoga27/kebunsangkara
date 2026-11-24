<?php

use App\Models\Article;
use App\Models\Faq;
use App\Models\Product;
use Symfony\Component\Finder\Finder;

$english = ['en' => [
    'name' => 'Local Durian',
    'short_description' => 'Sweet and creamy.',
    'description' => 'See [our durian](/buah/durian) and [seedlings](/bibit).',
]];

test('the root URL redirects permanently to the default language', function () {
    $this->get('/')->assertStatus(301)->assertRedirect('/id');
});

test('URLs without a language redirect permanently to the Indonesian page', function (string $old, string $new) {
    $this->get($old)->assertStatus(301)->assertRedirect($new);
})->with([
    'list' => ['/produk', '/id/produk'],
    'detail' => ['/produk/durian-lokal', '/id/produk/durian-lokal'],
    'hyphenated page' => ['/produk-kesehatan', '/id/produk-kesehatan'],
    'query string' => ['/artikel?page=2', '/id/artikel?page=2'],
    'contact' => ['/kontak', '/id/tentang-kami#kontak'],
]);

test('unknown languages return not found', function () {
    $this->get('/fr/produk')->assertNotFound();
});

test('every public page renders in both languages with matching alternates', function (string $path) {
    foreach (['id', 'en'] as $locale) {
        $this->get("/{$locale}{$path}")
            ->assertOk()
            ->assertSee('<html lang="'.$locale.'">', false)
            ->assertSee('<link rel="canonical" href="'.url("/{$locale}{$path}").'">', false)
            ->assertSee('<link rel="alternate" hreflang="id" href="'.url("/id{$path}").'">', false)
            ->assertSee('<link rel="alternate" hreflang="en" href="'.url("/en{$path}").'">', false)
            ->assertSee('<link rel="alternate" hreflang="x-default" href="'.url("/id{$path}").'">', false)
            ->assertSee('content="index, follow', false);
    }
})->with(['', '/produk', '/bibit', '/buah/durian', '/artikel', '/tentang-kami', '/pupuk', '/produk-kesehatan', '/service']);

test('interface texts and internal links follow the language', function () {
    $this->get('/en')
        ->assertSee('Our Fruit')
        ->assertSee('href="'.url('/en/buah/alpukat').'"', false)
        ->assertSee('href="/en/buah/durian"', false)
        ->assertDontSee('Buah Kami');

    $this->get('/id')->assertSee('Buah Kami')->assertSee('href="/id/buah/durian"', false);
});

test('a translated product shows its English content and is indexable', function () use ($english) {
    $product = Product::factory()->create(['name' => 'Durian Lokal', 'price' => 75000, 'translations' => $english]);

    $this->get(route('products.show', ['locale' => 'en', 'product' => $product]))
        ->assertSee('Local Durian')
        ->assertSee('IDR 75,000')
        ->assertSee('href="/en/bibit"', false)
        ->assertSee('content="index, follow', false)
        ->assertDontSee('Durian Lokal');

    $this->get(route('products.show', ['locale' => 'id', 'product' => $product]))
        ->assertSee('Durian Lokal')
        ->assertSee('Rp 75.000')
        ->assertDontSee('Local Durian');
});

test('an untranslated product falls back to Indonesian and is kept out of the English index', function () {
    $product = Product::factory()->create(['name' => 'Durian Lokal', 'translations' => ['en' => ['name' => 'Local Durian']]]);

    $this->get(route('products.show', ['locale' => 'en', 'product' => $product]))
        ->assertOk()
        ->assertSee($product->short_description)
        ->assertSee('content="noindex, follow"', false)
        ->assertDontSee('rel="alternate" hreflang', false);

    $this->get('/sitemap.xml')
        ->assertSee('<loc>'.url("/id/produk/{$product->slug}").'</loc>', false)
        ->assertDontSee(url("/en/produk/{$product->slug}"));
});

test('English lists only contain translated articles and FAQs', function () {
    Article::factory()->create(['title' => 'Musim Durian', 'translations' => ['en' => ['title' => 'Durian Season', 'excerpt' => 'When?', 'body' => 'Soon.']]]);
    Article::factory()->create(['title' => 'Cara Menanam']);
    Faq::factory()->create(['question' => 'Bisa diantar?', 'is_published' => true, 'translations' => ['en' => ['question' => 'Do you deliver?', 'answer' => 'Yes.']]]);
    Faq::factory()->create(['question' => 'Bisa COD?', 'is_published' => true]);

    $this->get('/en/artikel')->assertSee('Durian Season')->assertDontSee('Cara Menanam');
    $this->get('/en')->assertSee('Do you deliver?')->assertDontSee('Bisa COD?');
});

test('the sitemap lists translated pages in both languages with alternates', function () use ($english) {
    $product = Product::factory()->create(['translations' => $english]);

    $this->get('/sitemap.xml')
        ->assertOk()
        ->assertSee('<loc>'.url("/id/produk/{$product->slug}").'</loc>', false)
        ->assertSee('<loc>'.url("/en/produk/{$product->slug}").'</loc>', false)
        ->assertSee('<xhtml:link rel="alternate" hreflang="en" href="'.url("/en/produk/{$product->slug}").'"/>', false);
});

test('WhatsApp messages use the language of the page', function () use ($english) {
    $product = Product::factory()->create(['translations' => $english]);

    $location = $this->get("/en/pesan/{$product->slug}")->assertRedirect()->headers->get('Location');

    expect(rawurldecode($location))
        ->toContain('I would like to order *Local Durian*')
        ->toContain(url("/en/produk/{$product->slug}"));
});

test('every interface text has an English translation', function () {
    $translations = json_decode((string) file_get_contents(lang_path('en.json')), true, flags: JSON_THROW_ON_ERROR);
    $files = Finder::create()->files()->name('*.php')->in([
        resource_path('views/site'), resource_path('views/components/site'), resource_path('views/components/layouts'),
        app_path('Enums'), app_path('Models'), app_path('Http/Controllers/Site'),
    ]);

    $missing = collect($files)->flatMap(function ($file): array {
        preg_match_all("/__\('([^']+)'/", $file->getContents(), $matches);

        return $matches[1];
    })->reject(fn (string $key): bool => array_key_exists($key, $translations));

    expect($missing->values()->all())->toBe([]);
});
