@php
    $productImage = $product->displayImageUrl();
    $schemas = [
        [
            '@type' => 'Product',
            'name' => $product->name,
            'description' => $product->seoDescription(),
            'image' => [$productImage],
            'sku' => 'KS-'.$product->id,
            'category' => trim(Str::after($product->badge(), ' ')),
            'brand' => ['@type' => 'Brand', 'name' => $site->get('site_name')],
            'countryOfOrigin' => 'ID',
            'offers' => [
                '@type' => 'Offer',
                'url' => route('products.show', $product),
                'priceCurrency' => 'IDR',
                'price' => $product->price,
                'priceValidUntil' => now()->addMonths(3)->toDateString(),
                'itemCondition' => 'https://schema.org/NewCondition',
                'availability' => $product->is_available ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'seller' => ['@id' => route('home').'#store'],
                'areaServed' => ['@type' => 'State', 'name' => 'Bali'],
            ],
        ],
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => route('home')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => $product->listingPage()['label'], 'item' => $product->listingPage()['url']],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $product->name, 'item' => route('products.show', $product)],
            ],
        ],
    ];
@endphp

<x-layouts.site
    :title="$product->seoTitle()"
    :description="$product->seoDescription()"
    :canonical="route('products.show', $product)"
    :image="$productImage"
    type="product"
    :schemas="$schemas"
>
    <x-slot:head>
        <meta property="product:price:amount" content="{{ $product->price }}">
        <meta property="product:price:currency" content="IDR">
    </x-slot:head>

    <div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6">
        <div data-reveal>
            <x-site.breadcrumbs :items="[
                ['label' => 'Beranda', 'url' => route('home')],
                $product->listingPage(),
                ['label' => $product->name, 'url' => null],
            ]" />
        </div>

        <div class="mt-8 grid items-start gap-12 lg:grid-cols-2">
            {{-- Stage --}}
            <div data-spotlight data-reveal="zoom" class="grain relative isolate aspect-square overflow-hidden rounded-[2.5rem] bg-husk-950 lg:sticky lg:top-28">
                <div class="spotlight pointer-events-none absolute inset-0 -z-10"></div>
                <div class="absolute inset-0 overflow-hidden" data-depth="-12">
                    <img src="{{ $productImage }}" alt="{{ $product->imageAlt() }}" width="640" height="640" fetchpriority="high" class="size-full scale-110 object-cover">
                </div>
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-husk-950/50 via-transparent to-transparent"></div>
                <img src="{{ asset('images/leaf.svg') }}" alt="" class="absolute -right-4 top-[8%] w-[22%] -rotate-12 animate-bob drop-shadow-xl" data-depth="35">
                <span class="absolute bottom-5 left-5 rounded-full bg-white/90 px-4 py-1.5 text-sm font-bold text-husk-900 backdrop-blur">{{ $product->badge() }}</span>
                @if ($product->is_featured)
                    <span class="absolute left-5 top-5 rounded-full bg-flesh-400 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-husk-950">★ Terlaris</span>
                @endif
            </div>

            {{-- Info --}}
            <div class="lg:py-6">
                @if ($product->origin)
                    <p data-reveal class="inline-flex items-center gap-2 rounded-full bg-husk-100 px-4 py-1.5 text-sm font-semibold text-husk-800">📍 {{ $product->origin }}</p>
                @endif
                <h1 data-reveal style="--reveal-delay: 80ms" class="mt-5 font-display text-5xl font-bold leading-[1.05] tracking-tight text-husk-950 sm:text-6xl">{{ $product->name }}</h1>
                <p data-reveal style="--reveal-delay: 160ms" class="mt-5 text-lg leading-relaxed text-stone-600">{{ $product->short_description }}</p>

                <div data-reveal style="--reveal-delay: 240ms" class="mt-8 flex flex-wrap items-end gap-x-4 gap-y-2">
                    <p class="font-display text-5xl font-bold text-husk-800">{{ $product->formattedPrice() }}</p>
                    <p class="pb-2 text-stone-500">{{ $product->price_unit }}</p>
                </div>
                <p data-reveal style="--reveal-delay: 280ms" @class(['mt-3 inline-flex items-center gap-2 text-sm font-semibold', 'text-husk-700' => $product->is_available, 'text-red-700' => ! $product->is_available])>
                    <span class="relative flex size-2.5">
                        @if ($product->is_available)<span class="absolute inset-0 animate-ping-slow rounded-full bg-husk-500"></span>@endif
                        <span @class(['relative size-2.5 rounded-full', 'bg-husk-500' => $product->is_available, 'bg-red-500' => ! $product->is_available])></span>
                    </span>
                    {{ $product->is_available ? 'Stok tersedia hari ini' : ($product->type->hasFruitCategory() ? 'Stok sedang habis – tanyakan jadwal panen berikutnya' : 'Stok sedang habis – tanyakan ketersediaan berikutnya') }}
                </p>

                <dl class="mt-8 grid grid-cols-2 gap-3 text-sm" data-reveal-stagger="80">
                    @foreach (array_filter(! $product->type->hasFruitCategory() ? [
                        ['🏷️', 'Jenis', $product->variety],
                        ['🛵', 'Pengiriman', 'Seluruh Bali'],
                        ['💬', 'Tanya produk', 'Via WhatsApp'],
                    ] : ($product->isSeedling() ? [
                        ['🌳', 'Varietas', $product->variety],
                        ['✂️', 'Perbanyakan', $product->propagation_method],
                        ['📏', 'Tinggi bibit', $product->plant_height],
                        ['💬', 'Konsultasi tanam', 'Gratis via WhatsApp'],
                    ] : [
                        ['🌱', 'Varietas', $product->variety],
                        ['🛵', 'Pengiriman', 'Seluruh Bali'],
                        ['🛡️', 'Garansi', 'Ganti jika busuk'],
                        ['☀️', 'Kondisi', 'Segar dari kebun'],
                    ]), fn ($item) => filled($item[2])) as [$icon, $label, $value])
                        <div data-reveal class="flex items-center gap-3 rounded-2xl bg-white p-4 ring-1 ring-stone-200/70 transition hover:-translate-y-0.5 hover:ring-flesh-300">
                            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-flesh-100 text-lg">{{ $icon }}</span>
                            <div><dt class="text-xs text-stone-500">{{ $label }}</dt><dd class="font-semibold text-stone-900">{{ $value }}</dd></div>
                        </div>
                    @endforeach
                </dl>

                <div data-reveal class="mt-8">
                    <x-site.whatsapp-link :product="$product" size="lg" class="w-full sm:w-auto">
                        {{ $product->is_available ? 'Pesan '.$product->name : 'Tanya Ketersediaan' }}
                    </x-site.whatsapp-link>
                    <p class="mt-3 text-sm text-stone-500">Anda akan diarahkan ke WhatsApp {{ $site->get('phone_display') }} dengan pesan otomatis.</p>
                </div>

                <section data-reveal class="mt-12 rounded-[2rem] bg-white p-8 ring-1 ring-stone-200/70" aria-labelledby="deskripsi">
                    <h2 id="deskripsi" class="font-display text-3xl font-bold text-husk-950">Tentang {{ $product->name }}</h2>
                    <div class="prose-kebun mt-4">{{ $product->descriptionHtml() }}</div>
                </section>
            </div>
        </div>

        @if ($relatedProducts->isNotEmpty())
            <section class="mt-28" aria-labelledby="produk-lain">
                <h2 id="produk-lain" data-reveal class="font-display text-4xl font-bold text-husk-950">Produk Lainnya dari Kebun Kami</h2>
                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3" data-reveal-stagger="120">
                    @foreach ($relatedProducts as $related)
                        <div data-reveal><x-site.product-card :product="$related" class="h-full" /></div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>

    <x-site.cta-band />
</x-layouts.site>
