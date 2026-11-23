<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('site.products.index', [
            'products' => Product::query()->ordered()->get(),
            'faqs' => Faq::query()->where('is_published', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function show(Product $product): View
    {
        return view('site.products.show', [
            'product' => $product,
            'relatedProducts' => Product::query()
                ->whereKeyNot($product->getKey())
                ->where('is_available', true)
                ->ordered()
                ->limit(3)
                ->get(),
        ]);
    }
}
