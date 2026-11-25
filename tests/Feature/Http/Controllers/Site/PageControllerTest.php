<?php

use App\Models\Product;
use App\Models\Service;
use Database\Seeders\LayananSeeder;

it('renders an offering page that is marked active in the header', function (string $path, string $label) {
    $this->get($path)
        ->assertOk()
        ->assertSee('<title>'.$label, false)
        ->assertSee('/pesan?layanan='.basename($path), false)
        ->assertSeeInOrder(['href="'.url($path).'"', 'aria-current="page"', $label], false);
})->with([
    'pupuk' => ['/id/pupuk', 'Pupuk'],
    'obat pertanian' => ['/id/obat-pertanian', 'Obat Pertanian'],
    'produk kesehatan' => ['/id/produk-kesehatan', 'Produk Kesehatan'],
    'service' => ['/id/service', 'Layanan Kami'],
]);

it('marks the Produk submenu as active on its pages only', function (string $path, bool $active) {
    $response = $this->get($path)->assertOk();

    $active
        ? $response->assertSee('aria-current="true"', false)
        : $response->assertDontSee('aria-current="true"', false);
})->with([
    'hasil perkebunan' => ['/id/produk', true],
    'bibit' => ['/id/bibit', true],
    'pupuk' => ['/id/pupuk', true],
    'obat pertanian' => ['/id/obat-pertanian', true],
    'produk kesehatan' => ['/id/produk-kesehatan', true],
    'service' => ['/id/service', false],
    'beranda' => ['/id', false],
]);

it('lists only fertilizers on the pupuk page', function () {
    Product::factory()->fertilizer()->create(['name' => 'Kompos Organik']);
    Product::factory()->healthProduct()->create(['name' => 'Teh Manggis']);
    Product::factory()->create(['name' => 'Durian Lokal']);

    $this->get('/id/pupuk')
        ->assertOk()
        ->assertSee('Kompos Organik')
        ->assertDontSee('sedang disiapkan')
        ->assertDontSee('Teh Manggis')
        ->assertDontSee('Durian Lokal');
});

it('lists only health products on the produk kesehatan page', function () {
    Product::factory()->healthProduct()->create(['name' => 'Teh Manggis']);
    Product::factory()->fertilizer()->create(['name' => 'Kompos Organik']);

    $this->get('/id/produk-kesehatan')
        ->assertOk()
        ->assertSee('Teh Manggis')
        ->assertDontSee('Kompos Organik');
});

it('lists only crop protection products on the obat pertanian page, in both languages', function () {
    Product::factory()->cropProtection()->create(['name' => 'Fungisida Nabati']);
    Product::factory()->fertilizer()->create(['name' => 'Kompos Organik']);

    $this->get('/id/obat-pertanian')->assertOk()->assertSee('Fungisida Nabati')->assertDontSee('Kompos Organik');
    $this->get('/en/obat-pertanian')->assertOk()->assertSee('<title>Crop Protection', false)->assertDontSee('Obat Pertanian');
    $this->get('/id/pupuk')->assertOk()->assertDontSee('Fungisida Nabati');
});

it('shows a placeholder when an offering has no products', function () {
    $this->get('/id/pupuk')->assertOk()->assertSee('sedang disiapkan');
});

it('returns 404 for an unknown offering', function () {
    $this->get('/tidak-ada')->assertNotFound();
});

it('groups the product pages under a Produk submenu without a separate contact item', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSeeInOrder(['Menu utama', 'Beranda', '<summary', 'Produk', 'Hasil Perkebunan', 'Bibit', 'Pupuk', 'Obat Pertanian', 'Produk Kesehatan', '</details>', 'Layanan Kami', 'Artikel', 'Tentang Kami'], false)
        ->assertDontSee('href="'.url('/id/kontak').'"', false);
});

it('shows the contact details on the about page', function () {
    $this->get(route('about'))
        ->assertOk()
        ->assertSee('id="kontak"', false)
        ->assertSee('Hubungi Kami')
        ->assertSee('Area Pengiriman');
});

it('permanently redirects the old contact page to the about page', function () {
    $this->get('/kontak')
        ->assertMovedPermanently()
        ->assertRedirect('/id/tentang-kami#kontak');
});

it('calls the services page Layanan Kami in Indonesian and Services in English', function () {
    $this->get('/id')->assertSee('Layanan Kami');
    $this->get('/en')->assertSee('Services')->assertDontSee('Layanan Kami');
});

it('shows only published services that are not in the trash, in their order', function () {
    Service::factory()->create(['name' => 'Layanan Kedua', 'sort_order' => 2]);
    Service::factory()->create(['name' => 'Layanan Pertama', 'sort_order' => 1]);
    Service::factory()->create(['name' => 'Layanan Draf', 'is_published' => false]);
    Service::factory()->create(['name' => 'Layanan Terhapus'])->delete();

    $this->get('/id/service')
        ->assertOk()
        ->assertSeeInOrder(['Layanan Pertama', 'Layanan Kedua'])
        ->assertDontSee('Layanan Draf')
        ->assertDontSee('Layanan Terhapus')
        ->assertDontSee('sedang disiapkan');
});

it('shows services in English and falls back to Indonesian', function () {
    Service::factory()->create(['name' => 'Acara', 'translations' => ['en' => ['name' => 'Events', 'summary' => 'Farm events', 'description' => 'Join us']]]);
    Service::factory()->create(['name' => 'Belum Diterjemahkan']);

    $this->get('/en/service')->assertOk()->assertSee('Events')->assertSee('Farm events')->assertSee('Belum Diterjemahkan');
});

it('does not render HTML in the description of a service', function () {
    Service::factory()->create(['description' => "## Materi\n\n<script>alert('x')</script>"]);

    $this->get('/id/service')->assertSee('<h2>Materi</h2>', false)->assertDontSee("<script>alert('x')", false);
});

it('seeds the three services in both languages and keeps changes made in the admin panel', function () {
    $this->seed(LayananSeeder::class);

    $this->get('/en/service')->assertSeeInOrder(['Agricultural Education', 'Farm Consulting &amp; Assistance', 'Events'], false)->assertDontSee('Edukasi Pertanian');
    $this->get('/id/service')->assertSeeInOrder(['Edukasi Pertanian', 'Konsultasi &amp; Pendampingan Kebun', 'Acara'], false);

    foreach (Service::all() as $service) {
        foreach (Service::translatableAttributes() as $attribute => $maxLength) {
            expect(mb_strlen($service->$attribute))->toBeLessThanOrEqual($maxLength);
            expect(mb_strlen($service->translations['en'][$attribute]))->toBeGreaterThan(0)->toBeLessThanOrEqual($maxLength);
        }
    }

    Service::query()->firstWhere('name', 'Acara')->update(['summary' => 'Ringkasan saya']);
    Service::query()->firstWhere('name', 'Edukasi Pertanian')->delete();
    $this->seed(LayananSeeder::class);

    expect(Service::withTrashed()->count())->toBe(3);
    expect(Service::count())->toBe(2);
    expect(Service::query()->firstWhere('name', 'Acara')->summary)->toBe('Ringkasan saya');
});

it('shows a farm photo for services without their own photo', function () {
    Service::factory()->create(['sort_order' => 0]);
    Service::factory()->create(['sort_order' => 2]);
    Service::factory()->create(['sort_order' => 6, 'image_path' => 'services/acara.jpg']);

    $this->get('/id/service')
        ->assertOk()
        ->assertSee('images/kebun/alpukat-di-pohon.webp')
        ->assertSee('images/kebun/pohon-durian.webp')
        ->assertSee('/storage/services/acara.jpg');
});
