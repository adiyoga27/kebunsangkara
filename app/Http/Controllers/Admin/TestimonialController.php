<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveTestimonialRequest;
use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.testimonials.index', [
            'testimonials' => Testimonial::query()
                ->with('product')
                ->when($request->string('q')->trim()->value(), fn ($query, string $search) => $query->whereAny(['name', 'content'], 'like', "%{$search}%"))
                ->when($request->string('status')->value() === 'tampil', fn ($query) => $query->where('is_published', true))
                ->when($request->string('status')->value() === 'draf', fn ($query) => $query->where('is_published', false))
                ->latest()
                ->latest('id')
                ->paginate(20)
                ->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return $this->form(new Testimonial(['rating' => 5, 'is_published' => true]));
    }

    public function store(SaveTestimonialRequest $request): RedirectResponse
    {
        Testimonial::create($request->validated());

        return redirect()->route('admin.testimonials.index')->with('status', 'Testimoni berhasil ditambahkan.');
    }

    public function edit(Testimonial $testimonial): View
    {
        return $this->form($testimonial);
    }

    public function update(SaveTestimonialRequest $request, Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update($request->validated());

        return redirect()->route('admin.testimonials.index')->with('status', 'Testimoni berhasil diperbarui.');
    }

    public function togglePublication(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update(['is_published' => ! $testimonial->is_published]);

        return back()->with('status', $testimonial->is_published ? 'Testimoni sekarang tampil di website.' : 'Testimoni disembunyikan dari website.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')->with('status', 'Testimoni berhasil dihapus.');
    }

    private function form(Testimonial $testimonial): View
    {
        return view('admin.testimonials.form', [
            'testimonial' => $testimonial,
            'products' => Product::query()->ordered()->pluck('name', 'id'),
        ]);
    }
}
