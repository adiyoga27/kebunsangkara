<x-layouts.admin title="Produk">
    <x-admin.page-header title="Produk" description="Kelola produk buah, harga, dan stok.">
        <x-slot:actions><x-admin.button-link href="{{ route('admin.products.create') }}">+ Tambah Produk</x-admin.button-link></x-slot:actions>
    </x-admin.page-header>

    <form class="mb-4 flex flex-wrap gap-2">
        <input name="q" value="{{ request('q') }}" placeholder="Cari produk..." class="w-full max-w-xs rounded-xl border border-stone-300 bg-white px-3.5 py-2 text-sm">
        <select name="type" onchange="this.form.submit()" class="rounded-xl border border-stone-300 bg-white px-3.5 py-2 text-sm" aria-label="Tipe">
            <option value="">Buah &amp; bibit</option>
            @foreach (App\Enums\ProductType::cases() as $productType)
                <option value="{{ $productType->value }}" @selected(request('type') === $productType->value)>{{ $productType->label() }}</option>
            @endforeach
        </select>
        <select name="category" onchange="this.form.submit()" class="rounded-xl border border-stone-300 bg-white px-3.5 py-2 text-sm" aria-label="Kategori">
            <option value="">Semua kategori</option>
            @foreach (App\Enums\FruitCategory::cases() as $fruit)
                <option value="{{ $fruit->value }}" @selected(request('category') === $fruit->value)>{{ $fruit->label() }}</option>
            @endforeach
        </select>
    </form>

    <div class="overflow-x-auto rounded-2xl border border-stone-200 bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="bg-stone-50 text-xs uppercase tracking-wider text-stone-500">
                <tr><th class="px-4 py-3">Produk</th><th class="px-4 py-3">Kategori</th><th class="px-4 py-3">Harga</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Urutan</th><th class="px-4 py-3"></th></tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($products as $product)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $product->displayImageUrl() }}" alt="" class="size-12 rounded-lg object-cover">
                                <div>
                                    <a href="{{ route('products.show', $product) }}" target="_blank" class="font-semibold hover:underline">{{ $product->name }}</a>
                                    <p class="text-xs text-stone-500">/produk/{{ $product->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">{{ $product->isSeedling() ? '🌱 Bibit' : $product->category->emoji() }} {{ $product->category->label() }}</td>
                        <td class="px-4 py-3 font-medium">{{ $product->formattedPrice() }} <span class="text-xs text-stone-500">{{ $product->price_unit }}</span></td>
                        <td class="px-4 py-3">
                            <span @class(['rounded-full px-2.5 py-0.5 text-xs font-semibold', 'bg-husk-100 text-husk-800' => $product->is_available, 'bg-stone-200 text-stone-600' => ! $product->is_available])>{{ $product->is_available ? 'Tersedia' : 'Habis' }}</span>
                            @if ($product->is_featured)<span class="ml-1 rounded-full bg-flesh-100 px-2.5 py-0.5 text-xs font-semibold text-flesh-500">Unggulan</span>@endif
                        </td>
                        <td class="px-4 py-3">{{ $product->sort_order }}</td>
                        <td class="px-4 py-3"><x-admin.row-actions :edit="route('admin.products.edit', $product)" :destroy="route('admin.products.destroy', $product)" /></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-stone-500">Belum ada produk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $products->links() }}</div>
</x-layouts.admin>
