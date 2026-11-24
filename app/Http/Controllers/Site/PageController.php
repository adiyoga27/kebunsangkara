<?php

namespace App\Http\Controllers\Site;

use App\Enums\Offering;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('site.about', [
            'testimonials' => Testimonial::query()->where('is_published', true)->latest()->limit(3)->get(),
        ]);
    }

    public function offering(Offering $offering): View
    {
        $type = $offering->productType();

        return view('site.offering', [
            'offering' => $offering,
            'products' => $type ? Product::query()->ofType($type)->ordered()->get() : collect(),
        ]);
    }
}
