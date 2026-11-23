<?php

namespace App\Http\Controllers\Admin;

use App\Enums\FruitCategory;
use App\Http\Controllers\Admin\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveProductRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    use HandlesImageUploads;

    public function index(Request $request): View
    {
        return view('admin.products.index', [
            'products' => Product::query()
                ->when($request->string('q')->trim()->value(), fn ($query, string $search) => $query->where('name', 'like', "%{$search}%"))
                ->when(FruitCategory::tryFrom($request->string('category')->value()), fn ($query, FruitCategory $category) => $query->where('category', $category))
                ->ordered()
                ->paginate(20)
                ->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('admin.products.form', ['product' => new Product(['is_available' => true, 'price_unit' => 'per kg', 'category' => FruitCategory::Durian])]);
    }

    public function store(SaveProductRequest $request): RedirectResponse
    {
        Product::create([
            ...$request->safe()->except(['image', 'remove_image']),
            'image_path' => $this->resolveImagePath($request, 'products'),
        ]);

        return redirect()->route('admin.products.index')->with('status', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.form', ['product' => $product]);
    }

    public function update(SaveProductRequest $request, Product $product): RedirectResponse
    {
        $product->update([
            ...$request->safe()->except(['image', 'remove_image']),
            'image_path' => $this->resolveImagePath($request, 'products', $product->image_path),
        ]);

        return redirect()->route('admin.products.index')->with('status', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        $this->deleteImage($product->image_path);

        return redirect()->route('admin.products.index')->with('status', 'Produk berhasil dihapus.');
    }
}
