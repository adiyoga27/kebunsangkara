@php
    $schemas = [[
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => __('Beranda'), 'item' => route('home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => __('Bibit'), 'item' => route('seedlings.index')],
        ],
    ]];

    if ($seedlings->isNotEmpty()) {
        $schemas[] = [
            '@type' => 'ItemList',
            'name' => __('Bibit Buah :site', ['site' => $site->get('site_name')]),
            'itemListElement' => $seedlings->values()->map(fn ($seedling, $index) => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'url' => route('products.show', $seedling),
                'name' => $seedling->name,
            ])->all(),
        ];
    }

    $grouped = $seedlings->groupBy(fn ($seedling) => $seedling->category->value);
@endphp

<x-layouts.site
    :title="__('Jual Bibit Durian, Alpukat & Manggis Unggul di Bali')"
    :description="__('Bibit buah unggul dari :site Bali: bibit durian, alpukat & manggis hasil okulasi/sambung pucuk dari pohon induk kebun kami. Konsultasi tanam gratis.', ['site' => $site->get('site_name')])"
    :canonical="route('seedlings.index')"
    :image="asset('images/kebun/kebun-alpukat.webp')"
    :schemas="$schemas"
>
    <x-site.page-hero :title="__('Bibit Buah Unggul dari Kebun Kami')" :eyebrow="'🌱 '.__('Siap tanam')" image="images/kebun/kebun-alpukat.webp"
        :breadcrumbs="[['label' => __('Beranda'), 'url' => route('home')], ['label' => __('Bibit'), 'url' => null]]">
        {{ __('Ingin punya kebun sendiri? Kami menyediakan bibit durian, alpukat, dan manggis yang diperbanyak dari pohon induk unggul di kebun kami — lengkap dengan panduan tanam gratis.') }}
    </x-site.page-hero>

    <div class="mx-auto max-w-7xl px-4 pt-12 sm:px-6">
        <x-site.seedling-services />

        @forelse ($grouped as $categoryValue => $items)
            @php($fruit = App\Enums\FruitCategory::from($categoryValue))
            <section class="mt-20" aria-labelledby="bibit-{{ $fruit->value }}">
                <div class="flex flex-wrap items-end justify-between gap-4" data-reveal>
                    <h2 id="bibit-{{ $fruit->value }}" class="font-display text-3xl font-bold text-husk-950 sm:text-4xl">{{ __('Bibit :fruit', ['fruit' => $fruit->label()]) }}</h2>
                    <a href="{{ route('products.category', $fruit) }}" class="text-sm font-semibold text-husk-700 hover:underline">{{ __('Lihat semua :fruit', ['fruit' => Str::lower($fruit->label())]) }} &rarr;</a>
                </div>
                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3" data-reveal-stagger="100">
                    @foreach ($items as $seedling)
                        <div data-reveal><x-site.product-card :product="$seedling" heading-level="h3" class="h-full" /></div>
                    @endforeach
                </div>
            </section>
        @empty
            <div data-reveal class="mt-16 flex flex-col items-center rounded-[2rem] bg-white px-6 py-16 text-center ring-1 ring-stone-200/70">
                <span class="text-5xl">🌱</span>
                <p class="mt-4 font-display text-2xl font-bold text-husk-950">{{ __('Bibit sedang disiapkan') }}</p>
                <p class="mt-2 max-w-md text-stone-600">{{ __('Tanyakan ketersediaan bibit terbaru melalui WhatsApp.') }}</p>
                <x-site.whatsapp-link class="mt-6">{{ __('Tanya Stok Bibit') }}</x-site.whatsapp-link>
            </div>
        @endforelse

        @if ($faqs->isNotEmpty())
            <section class="mx-auto mt-24 max-w-3xl" aria-labelledby="faq">
                <h2 id="faq" data-reveal class="text-center font-display text-4xl font-bold text-husk-950">{{ __('Pertanyaan Seputar Bibit & Pemesanan') }}</h2>
                <div class="mt-8"><x-site.faq-list :faqs="$faqs" /></div>
            </section>
        @endif
    </div>

    <x-site.cta-band />
</x-layouts.site>
