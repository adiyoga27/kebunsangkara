<x-layouts.admin :title="$faq->exists ? 'Ubah FAQ' : 'Tambah FAQ'">
    <x-admin.page-header :title="$faq->exists ? 'Ubah FAQ' : 'Tambah FAQ'" />

    <form method="POST" action="{{ $faq->exists ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}" class="max-w-2xl space-y-6">
        @csrf
        @if ($faq->exists) @method('PUT') @endif
        <x-admin.card>
            <div class="space-y-4">
                <x-admin.input name="question" label="Pertanyaan" :value="$faq->question" required />
                <x-admin.textarea name="answer" label="Jawaban" :value="$faq->answer" rows="5" required />
                <x-admin.input name="sort_order" label="Urutan" type="number" min="0" :value="$faq->sort_order" />
                <x-admin.checkbox name="is_published" label="Tampilkan di website" :checked="$faq->is_published" />
            </div>
        </x-admin.card>
        <x-admin.form-actions :cancel="route('admin.faqs.index')" />
    </form>
</x-layouts.admin>
