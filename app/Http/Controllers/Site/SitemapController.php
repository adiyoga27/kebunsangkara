<?php

namespace App\Http\Controllers\Site;

use App\Enums\FruitCategory;
use App\Enums\Locale;
use App\Enums\Offering;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Product;
use DateTimeInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class SitemapController extends Controller
{
    public function sitemap(): Response
    {
        $latestProductUpdate = Product::query()->max('updated_at');

        $urls = collect([
            $this->page('home', [], $latestProductUpdate, '1.0'),
            $this->page('products.index', [], $latestProductUpdate, '0.9'),
            $this->page('seedlings.index', [], $latestProductUpdate, '0.9'),
            $this->page('articles.index', [], Article::query()->published()->max('updated_at'), '0.7'),
            ...collect(FruitCategory::cases())->map(fn (FruitCategory $category): Collection => $this->page('products.category', ['category' => $category], $latestProductUpdate, '0.9')),
            $this->page('about', [], null, '0.6'),
            ...collect(Offering::cases())->map(fn (Offering $offering): Collection => $this->page('offerings.show', ['offering' => $offering], null, '0.6')),
        ])
            ->concat(Product::query()->ordered()->get()->map(fn (Product $product): Collection => $this->page(
                'products.show', ['product' => $product], $product->updated_at, '0.8', $product->availableLocales(),
            )))
            ->concat(Article::query()->published()->latest('published_at')->get()->map(fn (Article $article): Collection => $this->page(
                'articles.show', ['article' => $article], $article->updated_at, '0.6', $article->availableLocales(),
            )))
            ->flatten(1);

        return response()
            ->view('site.sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    /**
     * One sitemap entry per language, each listing all language versions of the page.
     *
     * @param  array<string, mixed>  $parameters
     * @param  list<Locale>|null  $locales
     * @return Collection<int, array{loc: string, lastmod: DateTimeInterface|string|null, priority: string, alternates: array<string, string>}>
     */
    private function page(string $route, array $parameters, DateTimeInterface|string|null $lastModified, string $priority, ?array $locales = null): Collection
    {
        $alternates = collect($locales ?? Locale::cases())
            ->mapWithKeys(fn (Locale $locale): array => [$locale->value => route($route, [...$parameters, 'locale' => $locale->value])]);

        return $alternates->values()->map(fn (string $url): array => [
            'loc' => $url,
            'lastmod' => $lastModified,
            'priority' => $priority,
            'alternates' => $alternates->all(),
        ]);
    }

    public function robots(): Response
    {
        $orderPages = array_map(fn (Locale $locale): string => "Disallow: /{$locale->value}/pesan", Locale::cases());

        $lines = app()->isProduction()
            ? ['User-agent: *', 'Allow: /', 'Disallow: /admin', 'Disallow: /pesan', ...$orderPages, '', 'Sitemap: '.route('sitemap')]
            : ['User-agent: *', 'Disallow: /'];

        return response(implode("\n", $lines)."\n")->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
