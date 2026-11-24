<x-layouts.admin :title="$service->exists ? 'Ubah Layanan' : 'Tambah Layanan'">
    <x-admin.page-header :title="$service->exists ? 'Ubah Layanan' : 'Tambah Layanan'" />

    <form method="POST" enctype="multipart/form-data"
          action="{{ $service->exists ? route('admin.services.update', $service) : route('admin.services.store') }}" class="grid gap-6 lg:grid-cols-3">
        @csrf
        @if ($service->exists) @method('PUT') @endif

        <div class="min-w-0 lg:col-span-2">
            <x-admin.language-tabs :indonesian-fields="array_keys(App\Models\Service::translatableAttributes())" :english-fields="['translations.*']"
                                    :english-complete="$service->exists ? $service->isAvailableIn(App\Enums\Locale::English) : null">
                <x-slot:indonesian>
                    <x-admin.card title="Isi Layanan">
                        <div class="space-y-4">
                            <x-admin.input name="name" label="Nama Layanan" :value="$service->name" required maxlength="120" help="Contoh: Edukasi Pertanian" />
                            <x-admin.textarea name="summary" label="Ringkasan" :value="$service->summary" rows="2" maxlength="300" required />
                            <x-admin.textarea name="description" label="Isi Lengkap" :value="$service->description" rows="14" maxlength="10000" required data-rich-text />
                        </div>
                    </x-admin.card>
                </x-slot:indonesian>
                <x-slot:english>
                    <x-admin.translation-fields :model="$service" :rich-text="['description']" :fields="[['name', 'Nama Layanan', 1], ['summary', 'Ringkasan', 2], ['description', 'Isi Lengkap', 14]]" hint="Jika kosong, halaman Inggris menampilkan teks Indonesia." />
                </x-slot:english>
            </x-admin.language-tabs>
        </div>

        <div class="space-y-6">
            <x-admin.card title="Publikasi">
                <div class="space-y-4">
                    <x-admin.input name="sort_order" label="Urutan" type="number" min="0" :value="$service->sort_order" />
                    <x-admin.checkbox name="is_published" label="Tampilkan di website" :checked="$service->is_published" />
                </div>
            </x-admin.card>
            <x-admin.card title="Foto">
                <x-admin.image-field :current-url="$service->imageUrl()" help="Nama layanan tampil besar di atas foto ini, jadi pilih foto minimal lebar 1000 px. Jika kosong, dipakai foto kebun bawaan." />
            </x-admin.card>
            <x-admin.form-actions :cancel="route('admin.services.index')" />
        </div>
    </form>
</x-layouts.admin>
