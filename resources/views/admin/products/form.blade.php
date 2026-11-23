<x-layouts.admin :title="$product->exists ? 'Ubah Produk' : 'Tambah Produk'">
    <x-admin.page-header :title="$product->exists ? 'Ubah: '.$product->name : 'Tambah Produk'" />

    <form method="POST" enctype="multipart/form-data"
          action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" class="grid gap-6 lg:grid-cols-3">
        @csrf
        @if ($product->exists) @method('PUT') @endif

        <div class="space-y-6 lg:col-span-2">
            <x-admin.card title="Informasi Produk">
                <div class="space-y-4">
                    <x-admin.input name="name" label="Nama Produk" :value="$product->name" required help="Contoh: Durian Montong Bali, Alpukat Mentega, Jeruk Siam." />
                    <div>
                        <label for="category" class="block text-sm font-semibold text-stone-700">Kategori Buah</label>
                        <select id="category" name="category" class="mt-1.5 block w-full rounded-xl border border-stone-300 bg-white px-3.5 py-2.5 text-sm">
                            @foreach (App\Enums\FruitCategory::cases() as $fruit)
                                <option value="{{ $fruit->value }}" @selected(old('category', $product->category?->value) === $fruit->value)>{{ $fruit->emoji() }} {{ $fruit->label() }}</option>
                            @endforeach
                        </select>
                        @error('category')<p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <fieldset>
                        <legend class="block text-sm font-semibold text-stone-700">Tipe Produk</legend>
                        <div class="mt-1.5 grid grid-cols-2 gap-3">
                            @foreach (App\Enums\ProductType::cases() as $productType)
                                <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-stone-300 bg-white px-4 py-3 text-sm font-semibold has-[:checked]:border-husk-600 has-[:checked]:bg-husk-50 has-[:checked]:text-husk-800">
                                    <input type="radio" name="type" value="{{ $productType->value }}" @checked(old('type', $product->type?->value ?? 'buah') === $productType->value) class="accent-husk-700">
                                    {{ $productType->emoji() }} {{ $productType->label() }}
                                </label>
                            @endforeach
                        </div>
                        @error('type')<p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                    </fieldset>
                    <x-admin.input name="slug" label="Slug URL" :value="$product->slug" help="Kosongkan untuk dibuat otomatis dari nama. Contoh: alpukat-mentega-bali" />
                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-admin.input name="variety" label="Varietas" :value="$product->variety" />
                        <x-admin.input name="origin" label="Asal Kebun / Daerah" :value="$product->origin" help="Contoh: Kebun Sangkara, Tabanan" />
                    </div>
                    <div class="grid gap-4 rounded-xl bg-husk-50 p-4 sm:grid-cols-2">
                        <p class="text-xs font-semibold text-husk-800 sm:col-span-2">🌱 Khusus bibit (kosongkan untuk buah segar)</p>
                        <x-admin.input name="propagation_method" label="Metode Perbanyakan" :value="$product->propagation_method" help="Contoh: Okulasi, Sambung pucuk, Cangkok" />
                        <x-admin.input name="plant_height" label="Tinggi Bibit" :value="$product->plant_height" help="Contoh: 60–80 cm" />
                    </div>
                    <x-admin.textarea name="short_description" label="Deskripsi Singkat" :value="$product->short_description" rows="2" maxlength="300" required />
                    <x-admin.textarea name="description" label="Deskripsi Lengkap" :value="$product->description" rows="12" required
                                      help="Mendukung Markdown: ## Subjudul, **tebal**, - daftar. Tulis minimal 150 kata dengan kata kunci alami agar bagus di Google." />
                </div>
            </x-admin.card>
            <x-admin.seo-fields :model="$product" />
        </div>

        <div class="space-y-6">
            <x-admin.card title="Harga & Stok">
                <div class="space-y-4">
                    <x-admin.input name="price" label="Harga (Rp)" type="number" min="0" :value="$product->price" required />
                    <x-admin.input name="price_unit" label="Satuan" :value="$product->price_unit" required help="Contoh: per kg, per buah, per box, per batang" />
                    <x-admin.input name="sort_order" label="Urutan Tampil" type="number" min="0" :value="$product->sort_order ?? 0" />
                    <x-admin.checkbox name="is_available" label="Stok tersedia" :checked="$product->is_available" />
                    <x-admin.checkbox name="is_featured" label="Tandai sebagai terlaris" :checked="$product->is_featured" />
                </div>
            </x-admin.card>
            <x-admin.card title="Foto">
                <x-admin.image-field :current-url="$product->imageUrl()" />
            </x-admin.card>
            <x-admin.form-actions :cancel="route('admin.products.index')" />
        </div>
    </form>
</x-layouts.admin>
