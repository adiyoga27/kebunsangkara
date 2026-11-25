<?php

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\DB;

it('renders a product page with Product structured data and price', function () {
    $product = Product::factory()->create(['name' => 'Durian Lokal Pupuan', 'price' => 75000, 'price_unit' => 'per buah']);

    $this->get(route('products.show', $product))
        ->assertOk()
        ->assertSee('<h1', false)
        ->assertSee('Durian Lokal Pupuan')
        ->assertSee('Rp 75.000')
        ->assertSee('"@type":"Product"', false)
        ->assertSee('"availability":"https://schema.org/InStock"', false);
});

it('marks unavailable products as out of stock in structured data', function () {
    $product = Product::factory()->unavailable()->create();

    $this->get(route('products.show', $product))
        ->assertOk()
        ->assertSee('"availability":"https://schema.org/OutOfStock"', false);
});

it('strips raw HTML from Markdown product descriptions', function () {
    $product = Product::factory()->create(['description' => "**Manis**\n\n<script>alert('x')</script>"]);

    $this->get(route('products.show', $product))
        ->assertOk()
        ->assertSee('<strong>Manis</strong>', false)
        ->assertDontSee("<script>alert('x')</script>", false);
});

it('lists all products on the price page', function () {
    Product::factory()->create(['name' => 'Durian Kupas Box']);

    $this->get(route('products.index'))->assertOk()->assertSee('Durian Kupas Box');
});

it('excludes products without a fruit category from the price page', function () {
    Product::factory()->fertilizer()->create(['name' => 'Kompos Organik']);

    $this->get(route('products.index'))->assertOk()->assertDontSee('Kompos Organik');
});

it('renders a product without a fruit category under its own listing page', function (string $state, string $path, string $image) {
    $product = Product::factory()->{$state}()->create(['name' => 'Produk Contoh']);

    $this->get(route('products.show', $product))
        ->assertOk()
        ->assertSee('Produk Contoh')
        ->assertSee(asset($image), false)
        ->assertSeeInOrder(['href="'.url($path).'"', 'aria-current="page"'], false)
        ->assertDontSee('Produk Contoh Segar');
})->with([
    'pupuk' => ['fertilizer', '/id/pupuk', 'images/pupuk.svg'],
    'obat pertanian' => ['cropProtection', '/id/obat-pertanian', 'images/obat-pertanian.svg'],
    'produk kesehatan' => ['healthProduct', '/id/produk-kesehatan', 'images/produk-kesehatan.svg'],
]);

it('shows a slider with every photo when a product has a gallery', function () {
    $product = Product::factory()->create(['image_path' => 'products/utama.jpg']);
    ProductImage::factory()->for($product)->create(['path' => 'products/kedua.jpg']);

    $this->get(route('products.show', $product))
        ->assertOk()
        ->assertSee('data-slider-track', false)
        ->assertSeeInOrder([asset('storage/products/utama.jpg'), asset('storage/products/kedua.jpg')], false);
});

it('shows no slider when a product has a single photo', function () {
    $this->get(route('products.show', Product::factory()->create()))
        ->assertOk()
        ->assertDontSee('data-slider', false);
});

it('returns 404 for an unknown product slug', function () {
    $this->get('/id/produk/tidak-ada')->assertNotFound();
});

it('shows only products of the requested fruit category', function () {
    Product::factory()->category('alpukat')->create(['name' => 'Alpukat Mentega']);
    Product::factory()->category('manggis')->create(['name' => 'Manggis Kaligesing']);

    $this->get(url('/id/buah/alpukat'))
        ->assertOk()
        ->assertSee('<title>'.'Jual Alpukat Mentega Bali Segar', false)
        ->assertSee('Alpukat Mentega')
        ->assertDontSee('Manggis Kaligesing');
});

it('returns 404 for an unknown fruit category', function () {
    $this->get('/id/buah/rambutan')->assertNotFound();
});

it('uses the category photo when a product has no uploaded image', function () {
    $product = Product::factory()->category('manggis')->create();

    $this->get(route('products.show', $product))
        ->assertOk()
        ->assertSee(asset('images/kebun/buah-manggis.webp'), false);
});

it('lists seedlings on the bibit page with their propagation details', function () {
    Product::factory()->seedling()->create(['name' => 'Bibit Alpukat Mentega', 'propagation_method' => 'Sambung pucuk']);
    Product::factory()->create(['name' => 'Alpukat Segar']);

    $this->get(route('seedlings.index'))
        ->assertOk()
        ->assertSee('Bibit Alpukat Mentega')
        ->assertSee('Sambung pucuk')
        ->assertDontSee('Alpukat Segar');
});

it('separates seedlings from fruit on the category page', function () {
    Product::factory()->category('manggis')->create(['name' => 'Manggis Kaligesing Segar']);
    Product::factory()->category('manggis')->seedling()->create(['name' => 'Bibit Manggis Kaligesing']);

    $this->get(url('/id/buah/manggis'))
        ->assertOk()
        ->assertSeeInOrder(['Manggis Kaligesing Segar', 'Bibit Manggis', 'Bibit Manggis Kaligesing']);
});

it('hides products whose fruit category is no longer sold without deleting them', function () {
    $product = Product::factory()->create(['name' => 'Jeruk Siam Bali']);
    DB::table('products')->where('id', $product->id)->update(['category' => 'jeruk']);

    $this->get(route('products.index'))->assertOk()->assertDontSee('Jeruk Siam Bali');
    $this->get(route('products.show', $product->slug))->assertNotFound();
    $this->get('/id/buah/jeruk')->assertNotFound();
    $this->get(route('sitemap'))->assertOk()->assertDontSee($product->slug);

    $this->assertDatabaseHas('products', ['id' => $product->id]);
});
