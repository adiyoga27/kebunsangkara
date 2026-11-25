<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveFruitCategoryRequest;
use App\Models\FruitCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FruitCategoryController extends Controller
{
    use HandlesImageUploads;

    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => FruitCategory::query()->withCount('products')->ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.form', ['category' => new FruitCategory([
            'emoji' => '🍎',
            'sort_order' => FruitCategory::query()->max('sort_order') + 1,
        ])]);
    }

    public function store(SaveFruitCategoryRequest $request): RedirectResponse
    {
        FruitCategory::create([
            ...$request->safe()->except(['image', 'remove_image']),
            'image_path' => $this->resolveImagePath($request, 'categories'),
        ]);

        return redirect()->route('admin.categories.index')->with('status', 'Kategori berhasil ditambahkan.');
    }

    public function edit(FruitCategory $category): View
    {
        return view('admin.categories.form', ['category' => $category]);
    }

    public function update(SaveFruitCategoryRequest $request, FruitCategory $category): RedirectResponse
    {
        $category->update([
            ...$request->safe()->except(['image', 'remove_image']),
            'image_path' => $this->resolveImagePath($request, 'categories', $category->image_path),
        ]);

        return redirect()->route('admin.categories.index')->with('status', 'Kategori berhasil diperbarui.');
    }

    /**
     * Only a category without products can be deleted. Products in the trash count too, so they can still be restored.
     */
    public function destroy(FruitCategory $category): RedirectResponse
    {
        if ($category->products()->withTrashed()->exists()) {
            return back()->withErrors(['category' => 'Kategori "'.$category->name.'" masih dipakai produk (termasuk produk di Sampah). Pindahkan atau hapus permanen produknya lebih dulu.']);
        }

        $category->delete();
        $this->deleteImage($category->image_path);

        return redirect()->route('admin.categories.index')->with('status', 'Kategori berhasil dihapus.');
    }
}
