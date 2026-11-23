<?php

use App\Enums\FruitCategory;
use App\Models\Product;

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

it('returns 404 for an unknown product slug', function () {
    $this->get('/produk/tidak-ada')->assertNotFound();
});

it('shows only products of the requested fruit category', function () {
    Product::factory()->category(FruitCategory::Alpukat)->create(['name' => 'Alpukat Mentega']);
    Product::factory()->category(FruitCategory::Jeruk)->create(['name' => 'Jeruk Siam']);

    $this->get(route('products.category', FruitCategory::Alpukat))
        ->assertOk()
        ->assertSee('<title>'.FruitCategory::Alpukat->seoTitle(), false)
        ->assertSee('Alpukat Mentega')
        ->assertDontSee('Jeruk Siam');
});

it('returns 404 for an unknown fruit category', function () {
    $this->get('/buah/rambutan')->assertNotFound();
});

it('uses the category photo when a product has no uploaded image', function () {
    $product = Product::factory()->category(FruitCategory::Manggis)->create();

    $this->get(route('products.show', $product))
        ->assertOk()
        ->assertSee(asset(FruitCategory::Manggis->imagePath()), false);
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
    Product::factory()->category(FruitCategory::Jeruk)->create(['name' => 'Jeruk Siam Segar']);
    Product::factory()->category(FruitCategory::Jeruk)->seedling()->create(['name' => 'Bibit Jeruk Siam']);

    $this->get(route('products.category', FruitCategory::Jeruk))
        ->assertOk()
        ->assertSeeInOrder(['Jeruk Siam Segar', 'Bibit Jeruk', 'Bibit Jeruk Siam']);
});
