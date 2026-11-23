<?php

use App\Enums\FruitCategory;
use App\Models\Article;
use App\Models\Product;

it('lists pages, products and published articles in the XML sitemap', function () {
    $product = Product::factory()->create();
    $published = Article::factory()->create();
    $draft = Article::factory()->draft()->create();

    $this->get(route('sitemap'))
        ->assertOk()
        ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
        ->assertSee('<loc>'.route('products.show', $product).'</loc>', false)
        ->assertSee('<loc>'.route('products.category', FruitCategory::Manggis).'</loc>', false)
        ->assertSee('<loc>'.route('articles.show', $published).'</loc>', false)
        ->assertDontSee(route('articles.show', $draft), false);
});

it('blocks all crawlers outside production', function () {
    $this->get(route('robots'))->assertOk()->assertSeeText("User-agent: *\nDisallow: /", false);
});

it('allows crawlers and references the sitemap in production', function () {
    app()->detectEnvironment(fn () => 'production');

    $this->get(route('robots'))
        ->assertOk()
        ->assertSee('Disallow: /admin', false)
        ->assertSee('Sitemap: '.route('sitemap'), false);
});
