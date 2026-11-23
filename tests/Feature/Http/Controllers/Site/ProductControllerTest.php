<?php

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
    $this->get('/durian/tidak-ada')->assertNotFound();
});
