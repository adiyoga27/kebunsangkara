@php($type = $product->type)

<x-layouts.admin :title="($product->exists ? 'Ubah ' : 'Tambah ').$type->label()">
    <x-admin.page-header :title="$product->exists ? 'Ubah: '.$product->name : 'Tambah '.$type->label()" :description="$type->emoji().' '.$type->label()" />

    <form method="POST" enctype="multipart/form-data"
          action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" class="grid gap-6 lg:grid-cols-3">
        @csrf
        @if ($product->exists) @method('PUT') @endif
        <input type="hidden" name="type" value="{{ $type->value }}">

        <div class="min-w-0 lg:col-span-2">
            <x-admin.language-tabs :indonesian-fields="array_keys(App\Models\Product::translatableAttributes())" :english-fields="['translations.*']"
                                    :english-complete="$product->exists ? $product->isAvailableIn(App\Enums\Locale::English) : null">
                <x-slot:indonesian>
                    <x-admin.card title="Informasi Produk">
                        <div class="space-y-4">
                            <x-admin.input name="name" label="Nama Produk" :value="$product->name" required :help="'Contoh: '.$type->exampleName().'.'" />
                            <x-admin.input name="variety" :label="$type->hasFruitCategory() ? 'Varietas' : 'Jenis'" :value="$product->variety" />
                            @if ($product->isSeedling())
                                <x-admin.input name="propagation_method" label="Metode Perbanyakan" :value="$product->propagation_method" help="Contoh: Okulasi, Sambung pucuk, Cangkok" />
                            @endif
                            <x-admin.input name="price_unit" label="Satuan" :value="$product->price_unit" required help="Contoh: per kg, per buah, per box, per batang" />
                            <x-admin.textarea name="short_description" label="Deskripsi Singkat" :value="$product->short_description" rows="2" maxlength="300" required />
                            <x-admin.textarea name="description" label="Deskripsi Lengkap" :value="$product->description" rows="12" required data-rich-text
                                              help="Tulis minimal 150 kata dengan kata kunci alami agar bagus di Google." />
                        </div>
                    </x-admin.card>
                    <x-admin.seo-fields :model="$product" />
                </x-slot:indonesian>
                <x-slot:english>
                    <x-admin.translation-fields :model="$product" :rich-text="['description']" :fields="array_values(array_filter([
                        ['name', 'Nama Produk', 1],
                        ['variety', $type->hasFruitCategory() ? 'Varietas' : 'Jenis', 1],
                        $product->isSeedling() ? ['propagation_method', 'Metode Perbanyakan', 1] : null,
                        ['price_unit', 'Satuan', 1],
                        ['short_description', 'Deskripsi Singkat', 2],
                        ['description', 'Deskripsi Lengkap', 12],
                        ['meta_title', 'Meta Title', 1],
                        ['meta_description', 'Meta Description', 2],
                    ]))" />
                </x-slot:english>
            </x-admin.language-tabs>
        </div>

        <div class="space-y-6">
            <x-admin.card title="Detail Produk">
                <div class="space-y-4">
                    @error('type')<p class="text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                    @if ($type->hasFruitCategory())
                        <div>
                            <label for="category" class="block text-sm font-semibold text-stone-700">Kategori Buah</label>
                            <select id="category" name="category" class="mt-1.5 block w-full rounded-xl border border-stone-300 bg-white px-3.5 py-2.5 text-sm">
                                @foreach (App\Enums\FruitCategory::cases() as $fruit)
                                    <option value="{{ $fruit->value }}" @selected(old('category', $product->category?->value) === $fruit->value)>{{ $fruit->emoji() }} {{ $fruit->label() }}</option>
                                @endforeach
                            </select>
                            @error('category')<p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                        </div>
                    @endif
                    <x-admin.input name="origin" :label="$type->hasFruitCategory() ? 'Asal Kebun / Daerah' : 'Asal / Produsen'" :value="$product->origin" help="Contoh: Kebun Sangkara, Tabanan" />
                    @if ($product->isSeedling())
                        <x-admin.input name="plant_height" label="Tinggi Bibit" :value="$product->plant_height" help="Contoh: 60–80 cm" />
                    @endif
                    <x-admin.input name="slug" label="Slug URL" :value="$product->slug" help="Kosongkan untuk dibuat otomatis dari nama. Contoh: alpukat-mentega-bali" />
                </div>
            </x-admin.card>
            <x-admin.card title="Harga & Stok">
                <div class="space-y-4">
                    <x-admin.input name="price" label="Harga (Rp)" type="number" min="0" :value="$product->price" required />
                    <x-admin.input name="sort_order" label="Urutan Tampil" type="number" min="0" :value="$product->sort_order ?? 0" />
                    <x-admin.checkbox name="is_available" label="Stok tersedia" :checked="$product->is_available" />
                    <x-admin.checkbox name="is_featured" label="Tandai sebagai terlaris" :checked="$product->is_featured" />
                </div>
            </x-admin.card>
            <x-admin.card title="Foto">
                <x-admin.image-field label="Foto Utama" :current-url="$product->imageUrl()" data-image-resize />
                <x-admin.gallery-field :product="$product" />
            </x-admin.card>
            <x-admin.form-actions :cancel="route('admin.products.index', ['type' => $type->value])" />
        </div>
    </form>
</x-layouts.admin>
