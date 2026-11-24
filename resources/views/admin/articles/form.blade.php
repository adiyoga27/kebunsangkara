<x-layouts.admin :title="$article->exists ? 'Ubah Artikel' : 'Tulis Artikel'">
    <x-admin.page-header :title="$article->exists ? 'Ubah Artikel' : 'Tulis Artikel'" />

    <form method="POST" enctype="multipart/form-data"
          action="{{ $article->exists ? route('admin.articles.update', $article) : route('admin.articles.store') }}" class="grid gap-6 lg:grid-cols-3">
        @csrf
        @if ($article->exists) @method('PUT') @endif

        <div class="space-y-6 lg:col-span-2">
            <x-admin.card>
                <div class="space-y-4">
                    <x-admin.input name="title" label="Judul" :value="$article->title" required help="Gunakan kata kunci yang dicari orang, mis. &quot;Cara Memilih Durian Bali yang Manis&quot;." />
                    <x-admin.input name="slug" label="Slug URL" :value="$article->slug" help="Kosongkan untuk dibuat otomatis dari judul." />
                    <x-admin.textarea name="excerpt" label="Ringkasan" :value="$article->excerpt" rows="2" maxlength="320" required />
                    <x-admin.textarea name="body" label="Isi Artikel" :value="$article->body" rows="22" required data-rich-text
                                      help="Idealnya 600+ kata." />
                </div>
            </x-admin.card>
            <x-admin.seo-fields :model="$article" />
            <x-admin.translation-fields :model="$article" :rich-text="['body']" :fields="[
                ['title', 'Judul', 1],
                ['excerpt', 'Ringkasan', 2],
                ['body', 'Isi Artikel', 22],
                ['meta_title', 'Meta Title', 1],
                ['meta_description', 'Meta Description', 2],
            ]" />
        </div>

        <div class="space-y-6">
            <x-admin.card title="Publikasi">
                <div class="space-y-4">
                    <x-admin.checkbox name="is_published" label="Terbitkan" :checked="$article->is_published" />
                    <x-admin.input name="published_at" label="Tanggal Terbit" type="datetime-local" :value="$article->published_at?->format('Y-m-d\TH:i')" />
                </div>
            </x-admin.card>
            <x-admin.card title="Gambar Utama">
                <x-admin.image-field :current-url="$article->imageUrl()" />
            </x-admin.card>
            <x-admin.form-actions :cancel="route('admin.articles.index')" />
        </div>
    </form>
</x-layouts.admin>
