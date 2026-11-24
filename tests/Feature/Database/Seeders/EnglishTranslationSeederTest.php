<?php

use App\Enums\Locale;
use App\Models\Article;
use App\Models\Faq;
use App\Models\Product;
use Database\Seeders\ContentSeeder;
use Database\Seeders\EnglishTranslationSeeder;

test('all starter content is available in English within the field limits', function () {
    $this->seed(ContentSeeder::class);

    foreach ([Product::class, Article::class, Faq::class] as $model) {
        expect($model::count())->toBeGreaterThan(0);

        foreach ($model::all() as $record) {
            expect($record->isAvailableIn(Locale::English))->toBeTrue();

            foreach ($record->translations['en'] as $attribute => $value) {
                expect(mb_strlen($value))->toBeLessThanOrEqual($model::translatableAttributes()[$attribute]);
            }
        }
    }
});

test('running the seeder again keeps existing English texts', function () {
    $this->seed(ContentSeeder::class);

    $product = Product::query()->firstWhere('slug', 'manggis-bali');
    $product->update(['translations' => ['en' => ['name' => 'My Own Name']]]);

    $this->seed(EnglishTranslationSeeder::class);

    expect($product->fresh()->translations)->toBe(['en' => ['name' => 'My Own Name']]);
});
