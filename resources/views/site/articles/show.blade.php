@php
    $articleImage = $article->imageUrl() ?? asset('images/kebun/kebun-alpukat.webp');
    $schemas = [
        [
            '@type' => 'BlogPosting',
            'headline' => $article->title,
            'description' => $article->seoDescription(),
            'image' => [$articleImage],
            'datePublished' => $article->published_at?->toAtomString(),
            'dateModified' => $article->updated_at?->toAtomString(),
            'inLanguage' => 'id-ID',
            'mainEntityOfPage' => route('articles.show', $article),
            'author' => $article->author
                ? ['@type' => 'Person', 'name' => $article->author->name]
                : ['@type' => 'Organization', 'name' => $site->get('site_name')],
            'publisher' => ['@id' => route('home').'#store'],
        ],
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => route('home')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Artikel', 'item' => route('articles.index')],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $article->title, 'item' => route('articles.show', $article)],
            ],
        ],
    ];
@endphp

<x-layouts.site
    :title="$article->seoTitle()"
    :description="$article->seoDescription()"
    :canonical="route('articles.show', $article)"
    :image="$articleImage"
    type="article"
    :schemas="$schemas"
>
    <x-slot:head>
        <meta property="article:published_time" content="{{ $article->published_at?->toAtomString() }}">
        <meta property="article:modified_time" content="{{ $article->updated_at?->toAtomString() }}">
    </x-slot:head>

    <article class="mx-auto max-w-3xl px-4 pt-6 sm:px-6">
        <div data-reveal>
            <x-site.breadcrumbs :items="[
                ['label' => 'Beranda', 'url' => route('home')],
                ['label' => 'Artikel', 'url' => route('articles.index')],
                ['label' => $article->title, 'url' => null],
            ]" />
        </div>

        <header class="mt-8" data-reveal>
            <h1 class="font-display text-4xl font-bold leading-[1.08] tracking-tight text-husk-950 sm:text-6xl">{{ $article->title }}</h1>
            <p class="mt-4 text-sm text-stone-500">
                <time datetime="{{ $article->published_at?->toDateString() }}">{{ $article->published_at?->translatedFormat('d F Y') }}</time>
                @if ($article->author) · oleh {{ $article->author->name }} @endif
                · {{ $article->readingMinutes() }} menit baca
            </p>
        </header>

        <div data-reveal="zoom" class="relative mt-10 aspect-[16/9] overflow-hidden rounded-[2rem] bg-gradient-to-br from-husk-900 to-husk-700">
            <div class="absolute left-1/2 top-1/2 size-2/3 -translate-x-1/2 -translate-y-1/2 animate-blob bg-flesh-400/30 blur-3xl"></div>
            <img src="{{ $articleImage }}" alt="{{ $article->title }}" width="768" height="432" fetchpriority="high"
                 class="relative size-full object-cover">
        </div>

        <div class="prose-kebun mt-10 text-lg">{{ $article->bodyHtml() }}</div>

        @if ($products->isNotEmpty())
            <aside data-reveal class="grain relative mt-16 overflow-hidden rounded-[2rem] bg-husk-950 p-8 text-white sm:p-10" aria-labelledby="produk-artikel">
                <img src="{{ asset('images/logo.svg') }}" alt="" class="absolute -right-6 -top-6 w-32 rotate-12 animate-float-slow opacity-90">
                <h2 id="produk-artikel" class="relative max-w-sm font-display text-3xl font-bold">Pesan Buah Segar dari Kebun Kami</h2>
                <ul class="relative mt-6 divide-y divide-white/10">
                    @foreach ($products as $product)
                        <li class="flex items-center justify-between gap-4 py-3">
                            <a href="{{ route('products.show', $product) }}" class="font-semibold text-flesh-200 hover:underline">{{ $product->name }}</a>
                            <span class="text-sm font-semibold">{{ $product->formattedPrice() }} <span class="font-normal text-husk-200">{{ $product->price_unit }}</span></span>
                        </li>
                    @endforeach
                </ul>
                <div class="relative mt-8"><x-site.whatsapp-link>Pesan via WhatsApp</x-site.whatsapp-link></div>
            </aside>
        @endif
    </article>

    @if ($latestArticles->isNotEmpty())
        <section class="mx-auto mt-28 max-w-7xl px-4 sm:px-6" aria-labelledby="artikel-lain">
            <h2 id="artikel-lain" data-reveal class="font-display text-4xl font-bold text-husk-950">Artikel Lainnya</h2>
            <div class="mt-10 grid gap-6 md:grid-cols-3" data-reveal-stagger="120">
                @foreach ($latestArticles as $latest)
                    <div data-reveal><x-site.article-card :article="$latest" /></div>
                @endforeach
            </div>
        </section>
    @endif
</x-layouts.site>
