<x-layouts.admin title="Kategori Buah">
    <x-admin.page-header title="Kategori Buah" description="Kategori untuk Hasil Perkebunan dan Bibit. Setiap kategori punya halaman sendiri di website.">
        <x-slot:actions><x-admin.button-link href="{{ route('admin.categories.create') }}">+ Tambah Kategori</x-admin.button-link></x-slot:actions>
    </x-admin.page-header>

    @error('category')
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800" role="alert">{{ $message }}</div>
    @enderror

    <div class="overflow-x-auto rounded-2xl border border-stone-200 bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="bg-stone-50 text-xs uppercase tracking-wider text-stone-500">
                <tr><th class="px-4 py-3">#</th><th class="px-4 py-3">Kategori</th><th class="px-4 py-3">Produk</th><th class="px-4 py-3">English</th><th class="px-4 py-3"></th></tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($categories as $category)
                    <tr>
                        <td class="px-4 py-3 text-stone-500">{{ $category->sort_order }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $category->displayImageUrl() }}" alt="" class="size-12 shrink-0 rounded-lg object-cover">
                                <div>
                                    <a href="{{ route('products.category', $category) }}" target="_blank" class="font-semibold hover:underline">{{ $category->emoji }} {{ $category->name }}</a>
                                    <p class="text-xs text-stone-500">/buah/{{ $category->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">{{ $category->products_count }}</td>
                        <td class="px-4 py-3 text-stone-600">{{ $category->isAvailableIn(App\Enums\Locale::English) ? 'Lengkap' : 'Belum lengkap' }}</td>
                        <td class="px-4 py-3"><x-admin.row-actions :edit="route('admin.categories.edit', $category)" :destroy="route('admin.categories.destroy', $category)" confirm="Yakin ingin menghapus kategori ini?" /></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-stone-500">Belum ada kategori.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.admin>
