<x-layouts.site
    title="Artikel & Tips Buah Segar{{ $articles->currentPage() > 1 ? ' – Halaman '.$articles->currentPage() : '' }}"
    description="Tips memilih durian, alpukat, jeruk & manggis yang matang, info musim buah di Bali, dan panduan dari {{ $site->get('site_name') }}."
    :canonical="$articles->currentPage() > 1 ? $articles->url($articles->currentPage()) : route('articles.index')"
>
    <x-site.page-hero title="Artikel & Tips Seputar Buah" eyebrow="Blog" image="images/kebun/alpukat-di-pohon.webp"
        :breadcrumbs="[['label' => 'Beranda', 'url' => route('home')], ['label' => 'Artikel', 'url' => null]]">
        Panduan memilih buah yang matang, info musim panen di Bali, sampai resep dan manfaat buah segar.
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
