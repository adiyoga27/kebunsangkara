<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Faq;
use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('site.home', [
            'products' => Product::query()->ordered()->limit(6)->get(),
            'categoryCounts' => Product::query()->where('is_available', true)->toBase()
                ->selectRaw('category, COUNT(*) as total')->groupBy('category')->pluck('total', 'category'),
            'testimonials' => Testimonial::query()->where('is_published', true)->latest()->limit(6)->get(),
            'faqs' => Faq::query()->where('is_published', true)->orderBy('sort_order')->get(),
            'articles' => Article::query()->published()->latest('published_at')->limit(3)->get(),
        ]);
    }
}
