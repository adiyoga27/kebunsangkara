<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Product;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(): View
    {
        return view('site.articles.index', [
            'articles' => Article::query()->published()->availableInCurrentLocale()->latest('published_at')->paginate(9),
        ]);
    }

    public function show(Article $article): View
    {
        abort_unless($article->is_published && $article->published_at?->isPast(), 404);

        return view('site.articles.show', [
            'article' => $article->load('author'),
            'latestArticles' => Article::query()->published()->availableInCurrentLocale()->whereKeyNot($article->getKey())->latest('published_at')->limit(3)->get(),
            'products' => Product::query()->where('is_available', true)->ordered()->limit(3)->get(),
        ]);
    }
}
