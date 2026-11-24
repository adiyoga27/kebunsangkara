<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ProductType;
use App\Http\Controllers\Admin\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * The trash: deleted products that can still be restored.
 */
class TrashedProductController extends Controller
{
    use HandlesImageUploads;

    public function index(Request $request): View
    {
        return view('admin.trashed-products.index', [
            'products' => Product::onlyTrashed()
                ->when($request->string('q')->trim()->value(), fn ($query, string $search) => $query->where('name', 'like', "%{$search}%"))
                ->when(ProductType::tryFrom($request->string('type')->value()), fn ($query, ProductType $type) => $query->where('type', $type))
                ->latest('deleted_at')
                ->latest('id')
                ->paginate(20)
                ->withQueryString(),
        ]);
    }

    /**
     * Restore a product from the trash.
     */
    public function update(Product $product): RedirectResponse
    {
        abort_unless($product->trashed(), 404);

        $product->restore();

        return redirect()->route('admin.trashed-products.index')->with('status', "Produk \"{$product->name}\" berhasil dipulihkan.");
    }

    /**
     * Delete a product and its photos permanently. Only products that are already in the trash qualify.
     */
    public function destroy(Product $product): RedirectResponse
    {
        abort_unless($product->trashed(), 404);

        $paths = [$product->image_path, ...$product->images->pluck('path')];

        $product->forceDelete();
        array_map($this->deleteImage(...), $paths);

        return redirect()->route('admin.trashed-products.index')->with('status', 'Produk berhasil dihapus permanen.');
    }
}
