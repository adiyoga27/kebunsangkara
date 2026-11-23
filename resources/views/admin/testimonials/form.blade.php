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
                <x-admin.textarea name="content" label="Ulasan" :value="$testimonial->content" rows="4" required />
                <x-admin.input name="rating" label="Rating (1–5)" type="number" min="1" max="5" :value="$testimonial->rating" required />
                <x-admin.checkbox name="is_published" label="Tampilkan di website" :checked="$testimonial->is_published" />
            </div>
        </x-admin.card>
        <x-admin.form-actions :cancel="route('admin.testimonials.index')" />
    </form>
</x-layouts.admin>
