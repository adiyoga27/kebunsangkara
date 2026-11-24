<?php

namespace App\Http\Controllers\Site;

use App\Enums\FruitCategory;
use App\Enums\ProductType;
use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('site.products.index', [
            'products' => Product::query()->withFruitCategory()->ordered()->get(),
            'faqs' => Faq::query()->where('is_published', true)->availableInCurrentLocale()->orderBy('sort_order')->get(),
        ]);
    }

    public function category(FruitCategory $category): View
    {
        $products = Product::query()->where('category', $category)->ordered()->get();

        return view('site.products.category', [
            'category' => $category,
            'products' => $products->reject->isSeedling()->values(),
            'seedlings' => $products->filter->isSeedling()->values(),
            'otherCategories' => collect(FruitCategory::cases())->reject(fn (FruitCategory $other): bool => $other === $category),
        ]);
    }

    public function seedlings(): View
    {
        return view('site.products.seedlings', [
            'seedlings' => Product::query()->ofType(ProductType::Bibit)->ordered()->get(),
            'faqs' => Faq::query()->where('is_published', true)->availableInCurrentLocale()->orderBy('sort_order')->get(),
        ]);
    }

    public function show(Product $product): View
    {
        return view('site.products.show', [
            'product' => $product->load('images'),
            'relatedProducts' => Product::query()
                ->whereKeyNot($product->getKey())
                ->orderByRaw('type = ? desc', [$product->type->value])
                ->orderByRaw('category = ? desc', [$product->category?->value])
                ->where('is_available', true)
                ->ordered()
                ->limit(3)
                ->get(),
        ]);
    }
}
