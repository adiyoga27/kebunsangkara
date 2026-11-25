@php
    $schemas = [
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => __('Beranda'), 'item' => route('home')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => __('Produk & Harga'), 'item' => route('products.index')],
                ['@type' => 'ListItem', 'position' => 3, 'name' => __(':fruit Bali', ['fruit' => $category->name]), 'item' => route('products.category', $category)],
            ],
        ],
    ];

    if ($products->isNotEmpty() || $seedlings->isNotEmpty()) {
        $schemas[] = [
            '@type' => 'ItemList',
            'name' => __('Harga :fruit :site', ['fruit' => $category->name, 'site' => $site->get('site_name')]),
            'itemListElement' => $products->concat($seedlings)->values()->map(fn ($product, $index) => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'url' => route('products.show', $product),
                'name' => $product->name,
            ])->all(),
        ];
    }
@endphp

<x-layouts.site
    :title="$category->seoTitle()"
    :description="$category->seoDescription()"
    :canonical="route('products.category', $category)"
    :image="$category->displayImageUrl()"
    :locales="$category->availableLocales()"
    :schemas="$schemas"
>
    <x-site.page-hero :title="__('Jual :fruit Segar dari Kebun Bali', ['fruit' => $category->name])" :eyebrow="$category->emoji.' '.$category->tagline" :image="$category->displayImageUrl()"
        :breadcrumbs="[
            ['label' => __('Beranda'), 'url' => route('home')],
            ['label' => __('Produk & Harga'), 'url' => route('products.index')],
            ['label' => $category->name, 'url' => null],
        ]">
        {{ $category->intro }}
    </x-site.page-hero>

    <div class="mx-auto max-w-7xl px-4 pt-12 sm:px-6">
        <x-site.category-nav :active="$category" />

        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3" data-reveal-stagger="100">
            @forelse ($products as $product)
                <div data-reveal><x-site.product-card :product="$product" heading-level="h2" class="h-full" /></div>
            @empty
                @if ($seedlings->isEmpty())
                <div data-reveal class="col-span-full flex flex-col items-center rounded-[2rem] bg-white px-6 py-16 text-center ring-1 ring-stone-200/70">
                    <span class="text-5xl">{{ $category->emoji }}</span>
                    <p class="mt-4 font-display text-2xl font-bold text-husk-950">{{ __(':fruit sedang menunggu musim panen', ['fruit' => $category->name]) }}</p>
                    <p class="mt-2 max-w-md text-stone-600">{{ __('Tanyakan jadwal panen berikutnya atau pesan lebih dulu melalui WhatsApp.') }}</p>
                    <x-site.whatsapp-link class="mt-6">{{ __('Tanya Stok :fruit', ['fruit' => $category->name]) }}</x-site.whatsapp-link>
                </div>
                @endif
            @endforelse
        </div>

        @if ($seedlings->isNotEmpty())
            <section class="mt-20 rounded-[2.5rem] bg-husk-50 p-6 sm:p-10" aria-labelledby="bibit-kategori">
                <div class="flex flex-wrap items-end justify-between gap-4" data-reveal>
                    <div>
                        <p class="text-sm font-bold uppercase tracking-[0.2em] text-husk-600">🌱 {{ __('Tanam sendiri') }}</p>
                        <h2 id="bibit-kategori" class="mt-2 font-display text-3xl font-bold text-husk-950 sm:text-4xl">{{ __('Bibit :fruit', ['fruit' => $category->name]) }}</h2>
                    </div>
                    <a href="{{ route('seedlings.index') }}" class="text-sm font-semibold text-husk-700 hover:underline">{{ __('Semua bibit') }} &rarr;</a>
                </div>
                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3" data-reveal-stagger="100">
                    @foreach ($seedlings as $seedling)
                        <div data-reveal><x-site.product-card :product="$seedling" class="h-full" /></div>
                    @endforeach
                </div>
            </section>
        @endif

        <section class="mt-24" aria-labelledby="buah-lain">
            <h2 id="buah-lain" data-reveal class="font-display text-4xl font-bold text-husk-950">{{ __('Buah Lain dari Kebun Kami') }}</h2>
            <div class="mt-10 grid gap-5 sm:grid-cols-2" data-reveal-stagger="120">
                @foreach ($otherCategories as $fruit)
                    <a data-reveal href="{{ route('products.category', $fruit) }}" class="group relative isolate flex h-56 flex-col justify-end overflow-hidden rounded-[2rem] p-6 text-white">
                        <img src="{{ $fruit->displayImageUrl() }}" alt="{{ __(':fruit segar', ['fruit' => $fruit->name]) }}" loading="lazy" class="absolute inset-0 -z-10 size-full object-cover transition duration-1000 group-hover:scale-110">
                        <span class="absolute inset-0 -z-10 bg-gradient-to-t from-husk-950/90 to-transparent"></span>
                        <h3 class="font-display text-2xl font-bold">{{ $fruit->emoji }} {{ $fruit->name }}</h3>
                        <p class="text-sm text-husk-100/85">{{ $fruit->tagline }} <span class="inline-block transition group-hover:translate-x-1">&rarr;</span></p>
                    </a>
                @endforeach
            </div>
        </section>
    </div>

    <x-site.cta-band />
</x-layouts.site>
