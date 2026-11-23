<x-layouts.site
    title="Artikel & Tips Durian Bali{{ $articles->currentPage() > 1 ? ' – Halaman '.$articles->currentPage() : '' }}"
    description="Kumpulan tips memilih durian, info musim durian di Bali, jenis-jenis durian Bali, dan panduan menikmati durian dari {{ $site->get('site_name') }}."
    :canonical="$articles->currentPage() > 1 ? $articles->url($articles->currentPage()) : route('articles.index')"
>
    <x-site.page-hero title="Artikel & Tips Seputar Durian Bali" eyebrow="Blog"
        :breadcrumbs="[['label' => 'Beranda', 'url' => route('home')], ['label' => 'Artikel', 'url' => null]]">
        Panduan memilih durian matang, info musim durian di Bali, sampai rekomendasi jenis durian terbaik.
    </x-site.page-hero>

    <div class="mx-auto max-w-7xl px-4 pt-16 sm:px-6">
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3" data-reveal-stagger="100">
            @forelse ($articles as $article)
                <div data-reveal><x-site.article-card :article="$article" /></div>
            @empty
                <p class="text-stone-500">Artikel segera hadir.</p>
            @endforelse
        </div>

        <div class="mt-12">{{ $articles->links() }}</div>
    </div>
</x-layouts.site>
