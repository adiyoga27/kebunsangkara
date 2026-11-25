<x-layouts.admin :title="$category->exists ? 'Ubah Kategori' : 'Tambah Kategori'">
    <x-admin.page-header :title="$category->exists ? 'Ubah Kategori' : 'Tambah Kategori'" />

    <form method="POST" enctype="multipart/form-data"
          action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" class="grid gap-6 lg:grid-cols-3">
        @csrf
        @if ($category->exists) @method('PUT') @endif

        <div class="min-w-0 lg:col-span-2">
            <x-admin.language-tabs :indonesian-fields="array_keys(App\Models\FruitCategory::translatableAttributes())" :english-fields="['translations.*']"
                                    :english-complete="$category->exists ? $category->isAvailableIn(App\Enums\Locale::English) : null">
                <x-slot:indonesian>
                    <x-admin.card title="Informasi Kategori">
                        <div class="space-y-4">
                            <x-admin.input name="name" label="Nama Kategori" :value="$category->name" required maxlength="50" help="Contoh: Rambutan" />
                            <x-admin.input name="tagline" label="Slogan Singkat" :value="$category->tagline" required maxlength="100" help="Contoh: Daging tebal, lembut seperti mentega" />
                            <x-admin.textarea name="intro" label="Kata Pengantar" :value="$category->intro" rows="4" maxlength="500" required />
                        </div>
                    </x-admin.card>
                    <x-admin.seo-fields :model="$category" />
                </x-slot:indonesian>
                <x-slot:english>
                    <x-admin.translation-fields :model="$category" :fields="[
                        ['name', 'Nama Kategori', 1],
                        ['tagline', 'Slogan Singkat', 1],
                        ['intro', 'Kata Pengantar', 4],
                        ['meta_title', 'Meta Title', 1],
                        ['meta_description', 'Meta Description', 2],
                    ]" />
                </x-slot:english>
            </x-admin.language-tabs>
        </div>

        <div class="space-y-6">
            <x-admin.card title="Detail Kategori">
                <div class="space-y-4">
                    <x-admin.input name="emoji" label="Emoji" :value="$category->emoji" required maxlength="16" help="Contoh: 🍈 🥑 🍊 🍌" />
                    @if ($category->exists)
                        <p class="text-sm text-stone-600">Alamat halaman: <span class="font-semibold">/buah/{{ $category->slug }}</span> (tidak bisa diubah)</p>
                    @else
                        <x-admin.input name="slug" label="Slug URL" :value="$category->slug" :maxlength="App\Models\FruitCategory::MAX_SLUG_LENGTH" help="Kosongkan untuk dibuat otomatis dari nama. Tidak bisa diubah setelah disimpan." />
                    @endif
                    <x-admin.input name="sort_order" label="Urutan Tampil" type="number" min="0" :value="$category->sort_order" />
                </div>
            </x-admin.card>
            <x-admin.card title="Foto">
                <x-admin.image-field :current-url="$category->imageUrl()" help="Dipakai di beranda, halaman kategori, dan produk yang belum punya foto. Jika kosong, dipakai foto kebun bawaan." />
            </x-admin.card>
            <x-admin.form-actions :cancel="route('admin.categories.index')" />
        </div>
    </form>
</x-layouts.admin>
