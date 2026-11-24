<x-layouts.admin :title="$testimonial->exists ? 'Ubah Testimoni' : 'Tambah Testimoni'">
    <x-admin.page-header :title="$testimonial->exists ? 'Ubah Testimoni' : 'Tambah Testimoni'" />

    <form method="POST" action="{{ $testimonial->exists ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}" class="max-w-2xl space-y-6">
        @csrf
        @if ($testimonial->exists) @method('PUT') @endif
        <x-admin.card>
            <div class="space-y-4">
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-admin.input name="name" label="Nama Pelanggan" :value="$testimonial->name" required />
                    <x-admin.input name="location" label="Lokasi" :value="$testimonial->location" help="Contoh: Denpasar" />
                </div>
                <div>
                    <label for="product_id" class="block text-sm font-semibold text-stone-700">Produk yang Dibeli</label>
                    <select id="product_id" name="product_id" class="mt-1.5 block w-full rounded-xl border border-stone-300 bg-white px-3.5 py-2.5 text-sm">
                        <option value="">— Tidak disebutkan —</option>
                        @foreach ($products as $id => $name)
                            <option value="{{ $id }}" @selected((string) old('product_id', $testimonial->product_id) === (string) $id)>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('product_id')<p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                </div>
                <x-admin.textarea name="content" label="Ulasan" :value="$testimonial->content" rows="4" required />
                <x-admin.input name="rating" label="Rating (1–5)" type="number" min="1" max="5" :value="$testimonial->rating" required />
                <x-admin.checkbox name="is_published" label="Tampilkan di website" :checked="$testimonial->is_published" />
            </div>
        </x-admin.card>
        <x-admin.translation-fields :model="$testimonial" :fields="[['content', 'Ulasan', 4]]" />
        <x-admin.form-actions :cancel="route('admin.testimonials.index')" />
    </form>
</x-layouts.admin>
