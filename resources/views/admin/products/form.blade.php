<x-layouts.admin :title="$product->exists ? 'Ubah Produk' : 'Tambah Produk'">
    <x-admin.page-header :title="$product->exists ? 'Ubah: '.$product->name : 'Tambah Produk'" />

    <form method="POST" enctype="multipart/form-data"
          action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" class="grid gap-6 lg:grid-cols-3">
        @csrf
        @if ($product->exists) @method('PUT') @endif

        <div class="space-y-6 lg:col-span-2">
            <x-admin.card title="Informasi Produk">
                <div class="space-y-4">
                    <x-admin.input name="name" label="Nama Produk" :value="$product->name" required help="Contoh: Durian Montong Bali, Durian Lokal Pupuan." />
                    <x-admin.input name="slug" label="Slug URL" :value="$product->slug" help="Kosongkan untuk dibuat otomatis dari nama. Contoh: durian-montong-bali" />
                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-admin.input name="variety" label="Varietas" :value="$product->variety" />
                        <x-admin.input name="origin" label="Asal Kebun / Daerah" :value="$product->origin" help="Contoh: Pupuan, Tabanan" />
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
                    <x-admin.input name="price_unit" label="Satuan" :value="$product->price_unit" required help="Contoh: per kg, per buah, per box" />
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
