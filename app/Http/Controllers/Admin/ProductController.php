<?php

namespace App\Http\Controllers\Admin;

use App\Enums\FruitCategory;
use App\Enums\ProductType;
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
                ->when(ProductType::tryFrom($request->string('type')->value()), fn ($query, ProductType $type) => $query->where('type', $type))
                ->ordered()
                ->paginate(20)
                ->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('admin.products.form', ['product' => new Product(['is_available' => true, 'price_unit' => 'per kg', 'category' => FruitCategory::Durian, 'type' => ProductType::Buah])]);
    }

    public function store(SaveProductRequest $request): RedirectResponse
    {
        $product = Product::create([
            ...$request->safe()->except(['image', 'remove_image', 'images', 'remove_images']),
            'image_path' => $this->resolveImagePath($request, 'products'),
        ]);

        $this->syncGallery($request, $product);

        return redirect()->route('admin.products.index')->with('status', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.form', ['product' => $product]);
    }

    public function update(SaveProductRequest $request, Product $product): RedirectResponse
    {
        $product->update([
            ...$request->safe()->except(['image', 'remove_image', 'images', 'remove_images']),
            'image_path' => $this->resolveImagePath($request, 'products', $product->image_path),
        ]);

        $this->syncGallery($request, $product);

        return redirect()->route('admin.products.index')->with('status', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $paths = [$product->image_path, ...$product->images->pluck('path')];

        $product->delete();
        array_map($this->deleteImage(...), $paths);

        return redirect()->route('admin.products.index')->with('status', 'Produk berhasil dihapus.');
    }

    /**
     * Remove and add gallery photos. Without a main photo, the first gallery photo takes its place.
     */
    private function syncGallery(SaveProductRequest $request, Product $product): void
    {
        foreach ($product->images()->whereKey($request->validated('remove_images', []))->get() as $image) {
            $image->delete();
            $this->deleteImage($image->path);
        }

        foreach ($request->file('images', []) as $file) {
            $product->images()->create(['path' => $file->store('products', 'public')]);
        }

        if (! $product->image_path && $image = $product->images()->first()) {
            $product->update(['image_path' => $image->path]);
            $image->delete();
        }
    }
}
