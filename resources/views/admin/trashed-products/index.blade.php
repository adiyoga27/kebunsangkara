<x-layouts.admin title="Sampah Produk">
    <x-admin.page-header title="Sampah Produk" description="Produk yang dihapus tidak tampil di website, tetapi data dan fotonya masih tersimpan dan bisa dipulihkan.">
        <x-slot:actions><x-admin.button-link href="{{ route('admin.products.index') }}">&larr; Kembali ke Produk</x-admin.button-link></x-slot:actions>
    </x-admin.page-header>

    <form class="mb-4 flex flex-wrap gap-2">
        <input name="q" value="{{ request('q') }}" placeholder="Cari produk..." aria-label="Cari produk" class="w-full max-w-xs rounded-xl border border-stone-300 bg-white px-3.5 py-2 text-sm">
        <select name="type" onchange="this.form.submit()" class="rounded-xl border border-stone-300 bg-white px-3.5 py-2 text-sm" aria-label="Tipe">
            <option value="">Semua tipe</option>
            @foreach (App\Enums\ProductType::cases() as $productType)
                <option value="{{ $productType->value }}" @selected(request('type') === $productType->value)>{{ $productType->label() }}</option>
            @endforeach
        </select>
    </form>

    <div class="overflow-x-auto rounded-2xl border border-stone-200 bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="bg-stone-50 text-xs uppercase tracking-wider text-stone-500">
                <tr><th class="px-4 py-3">Produk</th><th class="px-4 py-3">Kategori</th><th class="px-4 py-3">Harga</th><th class="px-4 py-3">Dihapus</th><th class="px-4 py-3"></th></tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($products as $product)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $product->displayImageUrl() }}" alt="" class="size-12 rounded-lg object-cover">
                                <div>
                                    <p class="font-semibold">{{ $product->name }}</p>
                                    <p class="text-xs text-stone-500">/produk/{{ $product->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">{{ $product->badge() }}</td>
                        <td class="px-4 py-3 font-medium">{{ $product->formattedPrice() }} <span class="text-xs text-stone-500">{{ $product->price_unit }}</span></td>
                        <td class="whitespace-nowrap px-4 py-3 text-stone-600">{{ $product->deleted_at->translatedFormat('d M Y H:i') }}</td>
                        <td class="px-4 py-3"><div class="flex justify-end gap-2">
                            <form method="POST" action="{{ route('admin.trashed-products.update', $product) }}">
                                @csrf
                                @method('PATCH')
                                <button class="rounded-lg border border-husk-300 px-3 py-1.5 text-xs font-semibold text-husk-800 hover:bg-husk-50">Pulihkan</button>
                            </form>
                            @can('administer')
                                <form method="POST" action="{{ route('admin.trashed-products.destroy', $product) }}" onsubmit="return confirm(@js('Hapus permanen "'.$product->name.'" beserta fotonya? Tindakan ini tidak bisa dibatalkan.'))">
                                    @csrf
                                    @method('DELETE')
                                    <button class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-50">Hapus Permanen</button>
                                </form>
                            @endcan
                        </div></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-stone-500">{{ request()->hasAny(['q', 'type']) ? 'Tidak ada produk yang cocok.' : 'Sampah kosong.' }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $products->links() }}</div>
</x-layouts.admin>
