<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ProductType;
use App\Http\Controllers\Admin\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveProductRequest;
use App\Models\FruitCategory;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    use HandlesImageUploads;

    /**
     * Products are managed per type; fruit is shown when no type is chosen.
     */
    public function index(Request $request): View
    {
        $type = $this->requestedType($request);

        return view('admin.products.index', [
            'type' => $type,
            'products' => Product::query()
                ->ofType($type)
                ->when($request->string('q')->trim()->value(), fn ($query, string $search) => $query->where('name', 'like', "%{$search}%"))
                ->when($type->hasFruitCategory() ? $request->string('category')->value() : null, fn ($query, string $category) => $query->where('category', $category))
                ->ordered()
                ->paginate(20)
                ->withQueryString(),
            'categories' => FruitCategory::query()->ordered()->get(),
            'typeCounts' => Product::query()->toBase()->selectRaw('type, COUNT(*) as total')->groupBy('type')->pluck('total', 'type'),
            'trashedCount' => Product::onlyTrashed()->count(),
        ]);
    }

    public function create(Request $request): View
    {
        $type = $this->requestedType($request);

        $categories = FruitCategory::query()->ordered()->get();

        return view('admin.products.form', [
            'product' => new Product([
                'is_available' => true,
                'price_unit' => $type->defaultPriceUnit(),
                'category' => $type->hasFruitCategory() ? $categories->first()?->slug : null,
                'type' => $type,
            ]),
            'categories' => $categories,
        ]);
    }

    public function store(SaveProductRequest $request): RedirectResponse
    {
        $product = Product::create([
            ...$request->safe()->except(['image', 'remove_image', 'images', 'remove_images']),
            'image_path' => $this->resolveImagePath($request, 'products'),
        ]);

        $this->syncGallery($request, $product);

        return redirect()->route('admin.products.index', ['type' => $product->type->value])->with('status', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.form', [
            'product' => $product,
            'categories' => FruitCategory::query()->ordered()->get(),
        ]);
    }

    public function update(SaveProductRequest $request, Product $product): RedirectResponse
    {
        $product->update([
            ...$request->safe()->except(['image', 'remove_image', 'images', 'remove_images']),
            'image_path' => $this->resolveImagePath($request, 'products', $product->image_path),
        ]);

        $this->syncGallery($request, $product);

        return redirect()->route('admin.products.index', ['type' => $product->type->value])->with('status', 'Produk berhasil diperbarui.');
    }

    /**
     * Move a product to the trash. Its photos are kept so it can be restored.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index', ['type' => $product->type->value])->with('status', 'Produk dipindahkan ke Sampah dan masih bisa dipulihkan.');
    }

    private function requestedType(Request $request): ProductType
    {
        return ProductType::tryFrom($request->string('type')->value()) ?? ProductType::Buah;
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
