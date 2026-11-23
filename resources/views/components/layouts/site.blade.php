@props([
    'title',
    'description',
    'canonical' => null,
    'image' => null,
    'type' => 'website',
    'schemas' => [],
    'noindex' => false,
    'fullTitle' => false,
    'transparentHeader' => false,
])

@php
    $siteName = $site->get('site_name');
    $pageTitle = $fullTitle ? $title : $title.' | '.$siteName;
    $canonical ??= url()->current();
    $image ??= asset('images/kebun/durian-matang-di-pohon.webp');

    $business = [
        '@type' => 'Store',
        '@id' => route('home').'#store',
        'name' => $siteName,
        'description' => $site->get('home_meta_description'),
        'url' => route('home'),
        'image' => $image,
        'telephone' => '+'.$site->whatsappNumber(),
        'priceRange' => 'Rp',
        'currenciesAccepted' => 'IDR',
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => $site->get('street_address'),
            'addressLocality' => $site->get('city'),
            'addressRegion' => $site->get('region'),
            'postalCode' => $site->get('postal_code'),
            'addressCountry' => 'ID',
        ],
        'areaServed' => ['@type' => 'State', 'name' => 'Bali'],
        'sameAs' => array_values(array_filter([
            $site->get('instagram_url'), $site->get('facebook_url'), $site->get('tiktok_url'), $site->get('google_maps_url'),
        ])),
    ];

    if ($site->get('latitude') && $site->get('longitude')) {
        $business['geo'] = ['@type' => 'GeoCoordinates', 'latitude' => (float) $site->get('latitude'), 'longitude' => (float) $site->get('longitude')];
    }

    $graph = [
        '@context' => 'https://schema.org',
        '@graph' => [
            $business,
            ['@type' => 'WebSite', '@id' => route('home').'#website', 'url' => route('home'), 'name' => $siteName, 'inLanguage' => 'id-ID', 'publisher' => ['@id' => route('home').'#store']],
            ...$schemas,
        ],
    ];

    $viewingSeedling = request()->route('product') instanceof App\Models\Product && request()->route('product')->isSeedling();

    $navigation = [
        ['label' => 'Beranda', 'route' => 'home', 'active' => request()->routeIs('home')],
        ['label' => 'Buah Segar', 'route' => 'products.index', 'active' => request()->routeIs('products.*') && ! $viewingSeedling],
        ['label' => 'Bibit', 'route' => 'seedlings.index', 'active' => request()->routeIs('seedlings.*') || $viewingSeedling],
        ['label' => 'Artikel', 'route' => 'articles.index', 'active' => request()->routeIs('articles.*')],
        ['label' => 'Tentang Kami', 'route' => 'about', 'active' => request()->routeIs('about')],
        ['label' => 'Kontak', 'route' => 'contact', 'active' => request()->routeIs('contact')],
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <script>document.documentElement.classList.add('js')</script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ Str::limit($description, 165) }}">
    <link rel="canonical" href="{{ $canonical }}">
    <meta name="robots" content="{{ $noindex ? 'noindex, follow' : 'index, follow, max-image-preview:large, max-snippet:-1' }}">
    <meta name="theme-color" content="#0f1c0a">

    <meta name="geo.region" content="ID-BA">
    <meta name="geo.placename" content="{{ $site->get('city') }}, Bali">
    @if ($site->get('latitude') && $site->get('longitude'))
        <meta name="geo.position" content="{{ $site->get('latitude') }};{{ $site->get('longitude') }}">
        <meta name="ICBM" content="{{ $site->get('latitude') }}, {{ $site->get('longitude') }}">
    @endif

    <meta property="og:locale" content="id_ID">
    <meta property="og:type" content="{{ $type }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ Str::limit($description, 200) }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ $image }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ Str::limit($description, 200) }}">
    <meta name="twitter:image" content="{{ $image }}">

    @if ($site->get('google_site_verification'))
        <meta name="google-site-verification" content="{{ $site->get('google_site_verification') }}">
    @endif

    <link rel="icon" href="{{ asset('images/logo.svg') }}" type="image/svg+xml">
    <link rel="sitemap" type="application/xml" href="{{ route('sitemap') }}">
    {{ $head ?? '' }}

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script type="application/ld+json">{!! json_encode($graph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>

    @if ($site->get('google_analytics_id'))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $site->get('google_analytics_id') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', @js($site->get('google_analytics_id')));
        </script>
    @endif
</head>
<body class="overflow-x-clip bg-cream font-sans text-stone-800 antialiased">
    <a href="#konten" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[60] focus:rounded-full focus:bg-white focus:px-4 focus:py-2">Langsung ke konten</a>

    <header data-header @if ($transparentHeader) data-transparent @endif class="site-header fixed inset-x-0 top-0 z-50">
        <div class="header-inner mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6">
            <a href="{{ route('home') }}" class="group flex items-center gap-2.5" aria-label="{{ $siteName }} – Beranda">
                <img src="{{ asset('images/logo.svg') }}" alt="" width="44" height="44" class="size-11 rounded-2xl shadow-lg shadow-husk-950/30 transition duration-500 group-hover:-rotate-12 group-hover:scale-105">
                <span class="leading-none">
                    <span class="block font-display text-xl font-bold tracking-tight">{{ $siteName }}</span>
                    <span class="mt-0.5 block text-[10px] font-semibold uppercase tracking-[0.25em] opacity-60">Buah Segar Bali</span>
                </span>
            </a>

            <nav aria-label="Menu utama" class="hidden items-center lg:flex">
                @foreach ($navigation as $item)
                    <a href="{{ route($item['route']) }}" class="nav-link px-4 py-2.5 text-sm font-semibold"
                       @if ($item['active']) aria-current="page" @endif>{{ $item['label'] }}</a>
                @endforeach
            </nav>

            <div class="flex items-center gap-2">
                <x-site.whatsapp-link class="hidden sm:inline-flex" size="sm">Pesan Sekarang</x-site.whatsapp-link>

                <details class="group relative lg:hidden">
                    <summary class="flex size-11 items-center justify-center rounded-full bg-current/10 ring-1 ring-current/15 backdrop-blur" aria-label="Buka menu">
                        <span class="relative block h-3 w-5" aria-hidden="true">
                            <span class="absolute inset-x-0 top-0 h-0.5 rounded bg-current transition duration-300 group-open:top-1.5 group-open:rotate-45"></span>
                            <span class="absolute inset-x-0 bottom-0 h-0.5 rounded bg-current transition duration-300 group-open:bottom-1 group-open:-rotate-45"></span>
                        </span>
                    </summary>
                    <nav aria-label="Menu seluler" class="mobile-menu absolute right-0 mt-3 w-72 origin-top-right rounded-3xl bg-white p-3 text-stone-800 shadow-2xl shadow-husk-950/20 ring-1 ring-stone-200">
                        @foreach ($navigation as $item)
                            <a href="{{ route($item['route']) }}" @class([
                                'flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold transition',
                                'bg-husk-50 text-husk-800' => $item['active'],
                                'hover:bg-stone-50' => ! $item['active'],
                            ])>
                                {{ $item['label'] }} <span aria-hidden="true" class="text-flesh-500">&rarr;</span>
                            </a>
                        @endforeach
                        <x-site.whatsapp-link class="mt-2 w-full">Pesan via WhatsApp</x-site.whatsapp-link>
                    </nav>
                </details>
            </div>
        </div>
    </header>

    <main id="konten" @class(['pt-24' => ! $transparentHeader])>
        {{ $slot }}
    </main>

    <footer class="grain relative mt-28 overflow-hidden bg-husk-950 text-husk-100">
        <div class="pointer-events-none absolute -right-40 -top-40 size-[30rem] animate-blob bg-husk-700/40 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-40 -left-20 size-96 animate-blob bg-flesh-500/10 blur-3xl [animation-delay:-6s]"></div>

        <div class="relative mx-auto max-w-7xl px-4 pt-20 sm:px-6">
            <div class="flex flex-col items-start justify-between gap-8 border-b border-white/10 pb-14 md:flex-row md:items-end">
                <p class="max-w-2xl font-display text-4xl font-bold leading-tight text-white sm:text-5xl">
                    Buah segar dari kebun, <span class="text-gradient-gold italic">tinggal satu chat</span> lagi.
                </p>
                <x-site.whatsapp-link size="lg">Chat WhatsApp</x-site.whatsapp-link>
            </div>

            <div class="grid gap-10 py-14 md:grid-cols-4">
                <div class="md:col-span-2">
                    <p class="font-display text-2xl font-bold text-white">{{ $siteName }}</p>
                    <p class="mt-3 max-w-md text-sm leading-relaxed text-husk-200/80">{{ $site->get('tagline') }}. Melayani pengiriman buah segar ke {{ $site->get('service_area') }}.</p>
                    <div class="mt-6 flex gap-3">
                        @foreach (['instagram_url' => 'Instagram', 'facebook_url' => 'Facebook', 'tiktok_url' => 'TikTok'] as $key => $label)
                            @if ($site->get($key))
                                <a href="{{ $site->get($key) }}" rel="noopener me" target="_blank" class="rounded-full bg-white/5 px-4 py-2 text-xs font-semibold ring-1 ring-white/10 transition hover:-translate-y-0.5 hover:bg-flesh-400 hover:text-husk-950">{{ $label }}</a>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-flesh-300">Buah Kami</p>
                    <ul class="mt-5 space-y-3 text-sm">
                        @foreach (App\Enums\FruitCategory::cases() as $fruit)
                            <li><a href="{{ route('products.category', $fruit) }}" class="inline-block transition hover:translate-x-1 hover:text-white">{{ $fruit->label() }} Bali</a></li>
                        @endforeach
                        <li><a href="{{ route('seedlings.index') }}" class="inline-block transition hover:translate-x-1 hover:text-white">Bibit Buah Unggul</a></li>
                    </ul>
                    <p class="mt-8 text-xs font-bold uppercase tracking-[0.2em] text-flesh-300">Jelajahi</p>
                    <ul class="mt-5 space-y-3 text-sm">
                        @foreach ($navigation as $item)
                            <li><a href="{{ route($item['route']) }}" class="inline-block transition hover:translate-x-1 hover:text-white">{{ $item['label'] }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <address class="not-italic">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-flesh-300">Kontak</p>
                    <ul class="mt-5 space-y-3 text-sm">
                        <li>{{ $site->get('street_address') }}, {{ $site->get('city') }}, {{ $site->get('region') }} {{ $site->get('postal_code') }}</li>
                        <li>WhatsApp: <a href="{{ route('whatsapp.order') }}" rel="nofollow" class="font-semibold text-white hover:underline">{{ $site->get('phone_display') }}</a></li>
                        @if ($site->get('email'))
                            <li><a href="mailto:{{ $site->get('email') }}" class="hover:text-white">{{ $site->get('email') }}</a></li>
                        @endif
                        <li>{{ $site->get('opening_hours') }}</li>
                    </ul>
                </address>
            </div>
        </div>

        <p class="relative select-none overflow-hidden whitespace-nowrap text-center font-display text-[18vw] font-bold leading-[0.8] text-white/[0.04]" aria-hidden="true">Kebun Sangkara</p>

        <div class="relative border-t border-white/10">
            <p class="mx-auto max-w-7xl px-4 py-6 text-xs text-husk-200/60 sm:px-6">&copy; {{ now()->year }} {{ $siteName }}. Buah segar &amp; bibit unggul dari kebun Bali – durian, alpukat, jeruk &amp; manggis.</p>
        </div>
    </footer>

    <a href="{{ route('whatsapp.order') }}" rel="nofollow"
       class="group fixed bottom-5 right-5 z-50 flex items-center gap-2 rounded-full bg-whatsapp p-3.5 font-semibold text-white shadow-xl shadow-whatsapp/30 transition hover:scale-105 hover:bg-whatsapp-dark sm:py-3 sm:pl-3.5 sm:pr-5"
       aria-label="Pesan buah via WhatsApp">
        <span class="absolute inset-0 animate-ping-slow rounded-full bg-whatsapp" aria-hidden="true"></span>
        <x-site.whatsapp-icon class="relative size-7 transition duration-500 group-hover:rotate-[360deg]" />
        <span class="relative hidden sm:inline">Chat WhatsApp</span>
    </a>
</body>
</html>
