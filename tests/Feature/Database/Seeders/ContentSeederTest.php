<?php

use App\Enums\Locale;
use App\Enums\ProductType;
use App\Models\Product;
use Database\Seeders\BibitSeeder;
use Database\Seeders\ContentSeeder;
use Database\Seeders\HasilPerkebunanSeeder;
use Database\Seeders\ProdukKesehatanSeeder;
use Database\Seeders\PupukSeeder;

it('seeds only products of its own type and does not duplicate them', function (string $seeder, ProductType $type, int $total) {
    $this->seed($seeder);
    $this->seed($seeder);

    expect(Product::query()->count())->toBe($total)
        ->and(Product::query()->ofType($type)->count())->toBe($total);
})->with([
    'hasil perkebunan' => [HasilPerkebunanSeeder::class, ProductType::Buah, 5],
    'bibit' => [BibitSeeder::class, ProductType::Bibit, 3],
    'pupuk' => [PupukSeeder::class, ProductType::Pupuk, 3],
    'produk kesehatan' => [ProdukKesehatanSeeder::class, ProductType::Kesehatan, 3],
]);

it('seeds every product type with pages that render', function () {
    $this->seed(ContentSeeder::class);

    expect(Product::query()->count())->toBe(14);

    Product::all()->each(fn (Product $product) => $this->get(route('products.show', $product))->assertOk()->assertSee($product->name));

    foreach (['/id', '/id/produk', '/id/bibit', '/id/pupuk', '/id/produk-kesehatan', '/sitemap.xml'] as $path) {
        $this->get($path)->assertOk();
    }
});

it('seeds fertilizers and health products in both languages and keeps changes made in the admin panel', function (string $seeder) {
    $other = Product::factory()->create();

    $this->seed($seeder);
    $products = Product::query()->whereKeyNot($other->getKey())->get();
    $products->each(fn (Product $product) => expect($product->isAvailableIn(Locale::English))->toBeTrue());
    $products->first()->update(['name' => 'Nama Saya', 'price' => 12345]);

    $this->seed($seeder);

    expect($products->first()->fresh())->name->toBe('Nama Saya')->price->toBe(12345);
    expect(Product::query()->count())->toBe(4);
})->with([PupukSeeder::class, ProdukKesehatanSeeder::class]);
