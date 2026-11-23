<?php

use App\Models\Article;
use App\Models\User;

it('creates an article authored by the current user and publishes it now when no date is given', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('admin.articles.store'), [
        'title' => 'Musim Durian di Bali',
        'excerpt' => 'Kapan musim durian?',
        'body' => '## Musim',
        'is_published' => '1',
    ])->assertRedirect(route('admin.articles.index'));

    $article = Article::sole();
    expect($article->slug)->toBe('musim-durian-di-bali')
        ->and($article->user_id)->toBe($user->id)
        ->and($article->published_at)->not->toBeNull();
});
