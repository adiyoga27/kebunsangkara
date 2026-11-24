<?php

use App\Models\Product;

it('renders an offering page that is marked active in the header', function (string $path, string $label) {
    $this->get($path)
        ->assertOk()
        ->assertSee('<title>'.$label, false)
        ->assertSee('/pesan?layanan='.basename($path), false)
        ->assertSeeInOrder(['href="'.url($path).'"', 'aria-current="page"', $label], false);
})->with([
    'pupuk' => ['/id/pupuk', 'Pupuk'],
    'produk kesehatan' => ['/id/produk-kesehatan', 'Produk Kesehatan'],
    'service' => ['/id/service', 'Service'],
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

it('shows a placeholder when an offering has no products', function () {
    $this->get('/id/pupuk')->assertOk()->assertSee('sedang disiapkan');
});

it('returns 404 for an unknown offering', function () {
    $this->get('/tidak-ada')->assertNotFound();
});

it('groups the product pages under a Produk submenu without a separate contact item', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSeeInOrder(['Menu utama', 'Beranda', '<summary', 'Produk', 'Hasil Perkebunan', 'Bibit', 'Pupuk', 'Produk Kesehatan', '</details>', 'Service', 'Artikel', 'Tentang Kami'], false)
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
