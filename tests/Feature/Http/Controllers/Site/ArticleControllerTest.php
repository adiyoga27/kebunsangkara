<?php

use App\Models\Article;

it('shows only published articles in the article list', function () {
    Article::factory()->create(['title' => 'Cara Memilih Durian']);
    Article::factory()->draft()->create(['title' => 'Draft Rahasia']);
    Article::factory()->scheduled()->create(['title' => 'Artikel Minggu Depan']);

    $this->get(route('articles.index'))
        ->assertOk()
        ->assertSee('Cara Memilih Durian')
        ->assertDontSee('Draft Rahasia')
        ->assertDontSee('Artikel Minggu Depan');
});

it('renders a published article with BlogPosting structured data', function () {
    $article = Article::factory()->create(['body' => "## Tips Pertama\n\nCium aromanya."]);

    $this->get(route('articles.show', $article))
        ->assertOk()
        ->assertSee('<h2>Tips Pertama</h2>', false)
        ->assertSee('"@type":"BlogPosting"', false);
});

it('returns 404 for draft and scheduled articles', function (string $state) {
    $article = Article::factory()->{$state}()->create();

    $this->get(route('articles.show', $article))->assertNotFound();
})->with(['draft', 'scheduled']);
