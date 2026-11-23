<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
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

    public function contact(): View
    {
        return view('site.contact');
    }
}
