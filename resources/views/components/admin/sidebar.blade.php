@props(['siteName'])

@php
    $managedProduct = request()->route('product');
    $managedType = match (true) {
        ! request()->routeIs('admin.products.*') => null,
        $managedProduct instanceof App\Models\Product => $managedProduct->type,
        default => App\Enums\ProductType::tryFrom(request()->string('type')->value()) ?? App\Enums\ProductType::Buah,
    };

    $productMenu = array_map(fn (App\Enums\ProductType $type): array => [
        'label' => $type->label(),
        'route' => 'admin.products.index',
        'parameters' => ['type' => $type->value],
        'active' => $managedType === $type ? 'admin.products.*' : [],
        'icon' => match ($type) {
            App\Enums\ProductType::Buah => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
            App\Enums\ProductType::Bibit => 'M12 20v-9m0 0c0-4-3-6-7-6 0 4 3 6 7 6zm0 2c0-3 2-5 6-5 0 4-2 6-6 6',
            App\Enums\ProductType::Pupuk => 'M6 8h12l1 12H5L6 8zm3 0a3 3 0 016 0',
            App\Enums\ProductType::Obat => 'M9 8h5v3l2 2v7a1 1 0 01-1 1H8a1 1 0 01-1-1v-7l2-2V8zm0 0V4h6l1 2h-2v2M6 5H5M10 16h3',
            App\Enums\ProductType::Kesehatan => 'M12 20s-7-4.4-7-10a4 4 0 017-2.6A4 4 0 0119 10c0 5.6-7 10-7 10z',
        },
    ], App\Enums\ProductType::cases());

    $groups = [
        '' => [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard', 'icon' => 'M3 12l9-9 9 9M5 10v10h14V10'],
        ],
        'Katalog' => [
            ...$productMenu,
            ['label' => 'Kategori Buah', 'route' => 'admin.categories.index', 'active' => 'admin.categories.*', 'icon' => 'M4 5h6v6H4zM14 5h6v6h-6zM4 15h6v4H4zM14 15h6v4h-6z'],
            ['label' => 'Sampah Produk', 'route' => 'admin.trashed-products.index', 'active' => 'admin.trashed-products.*', 'icon' => 'M5 7h14M10 11v6m4-6v6M6 7l1 13h10l1-13M9 7V4h6v3'],
        ],
        'Konten' => [
            ['label' => 'Layanan Kami', 'route' => 'admin.services.index', 'active' => 'admin.services.*', 'icon' => 'M9 6V5a2 2 0 012-2h2a2 2 0 012 2v1M4 8a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V8zm0 5h16m-8-1v2'],
            ['label' => 'Artikel', 'route' => 'admin.articles.index', 'active' => 'admin.articles.*', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l6 6v8a2 2 0 01-2 2zM7 8h5m-5 4h10m-10 4h10'],
            ['label' => 'Testimoni', 'route' => 'admin.testimonials.index', 'active' => 'admin.testimonials.*', 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.4-4 8-9 8a9.9 9.9 0 01-4.3-1L3 20l1.4-3.7A7.6 7.6 0 013 12c0-4.4 4-8 9-8s9 3.6 9 8z'],
            ['label' => 'FAQ', 'route' => 'admin.faqs.index', 'active' => 'admin.faqs.*', 'icon' => 'M8.2 9a4 4 0 017.6 1c0 2-3 3-3 3m.2 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ],
    ];

    if (auth()->user()->can('administer')) {
        $groups += [
            'Custom Page' => [
                ['label' => 'Home Page', 'route' => 'admin.home-page.edit', 'active' => 'admin.home-page.*', 'icon' => 'M4 5h16v14H4zM4 9h16M8 13h8m-8 3h5'],
            ],
            'Laporan' => [
                ['label' => 'Klik WhatsApp', 'route' => 'admin.whatsapp-clicks.index', 'active' => 'admin.whatsapp-clicks.*', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'],
                ['label' => 'Log Aktivitas', 'route' => 'admin.activity-logs.index', 'active' => 'admin.activity-logs.*', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
            ],
            'Sistem' => [
                ['label' => 'Pengguna', 'route' => 'admin.users.index', 'active' => 'admin.users.*', 'icon' => 'M17 20h5v-2a3 3 0 00-5.4-1.8M17 20H7m10 0v-2c0-.7-.1-1.3-.4-1.8M7 20H2v-2a3 3 0 015.4-1.8M7 20v-2c0-.7.1-1.3.4-1.8m0 0a5 5 0 019.2 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ['label' => 'Pengaturan', 'route' => 'admin.settings.edit', 'active' => 'admin.settings.*', 'icon' => 'M10.3 4.3c.4-1.7 3-1.7 3.4 0a1.7 1.7 0 002.6 1.1c1.5-.9 3.3.8 2.4 2.4a1.7 1.7 0 001 2.5c1.8.4 1.8 3 0 3.4a1.7 1.7 0 00-1 2.6c.9 1.5-.9 3.3-2.4 2.4a1.7 1.7 0 00-2.6 1c-.4 1.8-3 1.8-3.4 0a1.7 1.7 0 00-2.5-1c-1.6.9-3.3-.9-2.4-2.4a1.7 1.7 0 00-1.1-2.6c-1.7-.4-1.7-3 0-3.4a1.7 1.7 0 001.1-2.5c-.9-1.6.8-3.3 2.4-2.4 1 .6 2.3 0 2.5-1.1zM15 12a3 3 0 11-6 0 3 3 0 016 0z'],
            ],
        ];
    }

    $initials = Str::of(auth()->user()->name)->explode(' ')->filter()->take(2)->map(fn (string $word): string => Str::upper(Str::substr($word, 0, 1)))->implode('');
@endphp

{{-- Mobile top bar --}}
<header class="sticky top-0 z-30 flex items-center gap-3 border-b border-stone-200 bg-white/90 px-4 py-3 backdrop-blur lg:hidden">
    <button type="button" data-sidebar-open aria-controls="admin-sidebar" aria-expanded="false" aria-label="Buka menu"
            class="flex size-10 items-center justify-center rounded-xl text-stone-700 transition hover:bg-stone-100 focus-visible:outline-2 focus-visible:outline-husk-500">
        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h10"/></svg>
    </button>
    <img src="{{ asset('images/logo.svg') }}" alt="" class="size-8 rounded-full">
    <span class="truncate font-display text-lg font-bold text-husk-900">{{ $siteName }}</span>
</header>

<div data-sidebar-backdrop class="invisible fixed inset-0 z-40 bg-husk-950/60 opacity-0 backdrop-blur-sm transition-[opacity,visibility] duration-300 motion-reduce:transition-none data-open:visible data-open:opacity-100 lg:hidden"></div>

<aside id="admin-sidebar" data-sidebar
       class="invisible fixed inset-y-0 left-0 z-50 flex w-72 max-w-[85vw] -translate-x-full flex-col bg-gradient-to-b from-husk-950 via-husk-950 to-husk-900 text-husk-100 shadow-2xl transition-[translate,visibility] duration-300 motion-reduce:transition-none data-open:visible data-open:transition-[translate] data-open:translate-x-0 lg:visible lg:translate-x-0 lg:shadow-none">
    <div class="flex items-center gap-3 px-5 pb-5 pt-6">
        <a href="{{ route('admin.dashboard') }}" class="group flex min-w-0 flex-1 items-center gap-3 rounded-xl focus-visible:outline-2 focus-visible:outline-flesh-400">
            <img src="{{ asset('images/logo.svg') }}" alt="" class="size-11 shrink-0 rounded-full ring-2 ring-white/10 transition duration-300 group-hover:ring-flesh-400/60">
            <span class="min-w-0">
                <span class="block truncate font-display text-lg font-bold leading-tight text-white">{{ $siteName }}</span>
                <span class="block text-[11px] font-medium text-husk-200/70">Panel Admin</span>
            </span>
        </a>
        <button type="button" data-sidebar-close aria-label="Tutup menu" class="flex size-9 shrink-0 items-center justify-center rounded-xl text-husk-200 transition hover:bg-white/10 hover:text-white focus-visible:outline-2 focus-visible:outline-flesh-400 lg:hidden">
            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
        </button>
    </div>

    <nav class="sidebar-scroll min-h-0 flex-1 space-y-6 overflow-y-auto overscroll-contain px-3 pb-6" aria-label="Menu admin">
        @foreach ($groups as $heading => $items)
            <div>
                @if ($heading)
                    <p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-[0.14em] text-husk-200/60">{{ $heading }}</p>
                @endif
                <ul class="space-y-1">
                    @foreach ($items as $item)
                        @php($isActive = request()->routeIs($item['active']))
                        <li>
                            <a href="{{ route($item['route'], $item['parameters'] ?? []) }}" @if ($isActive) aria-current="page" @endif @class([
                                'group relative flex items-center gap-3 rounded-xl px-3 py-2 text-sm transition duration-200 focus-visible:outline-2 focus-visible:outline-flesh-400',
                                'bg-white/10 font-semibold text-white before:absolute before:inset-y-2 before:-left-3 before:w-1 before:rounded-r-full before:bg-flesh-400' => $isActive,
                                'font-medium text-husk-100/80 hover:bg-white/5 hover:text-white' => ! $isActive,
                            ])>
                                <span @class([
                                    'flex size-8 shrink-0 items-center justify-center rounded-lg transition duration-200',
                                    'bg-flesh-400 text-husk-950' => $isActive,
                                    'bg-white/5 text-husk-200 group-hover:bg-white/10 group-hover:text-flesh-300' => ! $isActive,
                                ])>
                                    <svg class="size-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/></svg>
                                </span>
                                <span class="truncate">{{ $item['label'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </nav>

    <div class="m-3 mt-0 rounded-2xl bg-white/5 p-2 ring-1 ring-white/10">
        <a href="{{ route('admin.profile.edit') }}" title="Profil Saya" class="flex items-center gap-3 rounded-xl p-2 transition hover:bg-white/5 focus-visible:outline-2 focus-visible:outline-flesh-400">
            <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-flesh-400 text-sm font-bold text-husk-950" aria-hidden="true">{{ $initials }}</span>
            <span class="min-w-0">
                <span class="block truncate text-sm font-semibold text-white">{{ auth()->user()->name }}</span>
                <span class="block truncate text-xs text-husk-200/70">{{ auth()->user()->role->label() }}</span>
            </span>
        </a>
        <div class="mt-1 grid grid-cols-2 gap-1 text-xs font-medium">
            <a href="{{ route('home') }}" target="_blank" rel="noopener" class="flex items-center justify-center gap-2 rounded-xl px-2 py-2 text-husk-100/80 transition hover:bg-white/10 hover:text-white focus-visible:outline-2 focus-visible:outline-flesh-400">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5h5v5m0-5l-8 8M18 14v4a1 1 0 01-1 1H6a1 1 0 01-1-1V7a1 1 0 011-1h4"/></svg>
                Website
            </a>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl px-2 py-2 text-husk-100/80 transition hover:bg-red-500/20 hover:text-red-200 focus-visible:outline-2 focus-visible:outline-flesh-400">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 8V6a1 1 0 00-1-1H6a1 1 0 00-1 1v12a1 1 0 001 1h8a1 1 0 001-1v-2M10 12h10m0 0l-3-3m3 3l-3 3"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </div>
</aside>
