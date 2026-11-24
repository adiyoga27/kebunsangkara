<?php

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
