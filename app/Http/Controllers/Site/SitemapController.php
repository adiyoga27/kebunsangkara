<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function sitemap(): Response
    {
        $latestProductUpdate = Product::query()->max('updated_at');

        $urls = collect([
            ['loc' => route('home'), 'lastmod' => $latestProductUpdate, 'priority' => '1.0'],
            ['loc' => route('products.index'), 'lastmod' => $latestProductUpdate, 'priority' => '0.9'],
            ['loc' => route('articles.index'), 'lastmod' => Article::query()->published()->max('updated_at'), 'priority' => '0.7'],
            ['loc' => route('about'), 'lastmod' => null, 'priority' => '0.5'],
            ['loc' => route('contact'), 'lastmod' => null, 'priority' => '0.6'],
        ])
            ->concat(Product::query()->ordered()->get(['slug', 'updated_at'])->map(fn (Product $product): array => [
                'loc' => route('products.show', $product),
                'lastmod' => $product->updated_at,
                'priority' => '0.8',
            ]))
            ->concat(Article::query()->published()->latest('published_at')->get(['slug', 'updated_at'])->map(fn (Article $article): array => [
                'loc' => route('articles.show', $article),
                'lastmod' => $article->updated_at,
                'priority' => '0.6',
            ]));

        return response()
            ->view('site.sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        $lines = app()->isProduction()
            ? ['User-agent: *', 'Allow: /', 'Disallow: /admin', 'Disallow: /pesan', '', 'Sitemap: '.route('sitemap')]
            : ['User-agent: *', 'Disallow: /'];

        return response(implode("\n", $lines)."\n")->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
