@props(['title'])

@php
    $menu = [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard', 'icon' => 'M3 12l9-9 9 9M5 10v10h14V10'],
        ['label' => 'Produk', 'route' => 'admin.products.index', 'active' => 'admin.products.*', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
        ['label' => 'Artikel', 'route' => 'admin.articles.index', 'active' => 'admin.articles.*', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l6 6v8a2 2 0 01-2 2zM7 8h5m-5 4h10m-10 4h10'],
        ['label' => 'Testimoni', 'route' => 'admin.testimonials.index', 'active' => 'admin.testimonials.*', 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.4-4 8-9 8a9.9 9.9 0 01-4.3-1L3 20l1.4-3.7A7.6 7.6 0 013 12c0-4.4 4-8 9-8s9 3.6 9 8z'],
        ['label' => 'FAQ', 'route' => 'admin.faqs.index', 'active' => 'admin.faqs.*', 'icon' => 'M8.2 9a4 4 0 017.6 1c0 2-3 3-3 3m.2 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
    ];

    if (auth()->user()->can('administer')) {
        $menu = [...$menu,
            ['label' => 'Klik WhatsApp', 'route' => 'admin.whatsapp-clicks.index', 'active' => 'admin.whatsapp-clicks.*', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'],
            ['label' => 'Log Aktivitas', 'route' => 'admin.activity-logs.index', 'active' => 'admin.activity-logs.*', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
            ['label' => 'Pengguna', 'route' => 'admin.users.index', 'active' => 'admin.users.*', 'icon' => 'M17 20h5v-2a3 3 0 00-5.4-1.8M17 20H7m10 0v-2c0-.7-.1-1.3-.4-1.8M7 20H2v-2a3 3 0 015.4-1.8M7 20v-2c0-.7.1-1.3.4-1.8m0 0a5 5 0 019.2 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
            ['label' => 'Pengaturan', 'route' => 'admin.settings.edit', 'active' => 'admin.settings.*', 'icon' => 'M10.3 4.3c.4-1.7 3-1.7 3.4 0a1.7 1.7 0 002.6 1.1c1.5-.9 3.3.8 2.4 2.4a1.7 1.7 0 001 2.5c1.8.4 1.8 3 0 3.4a1.7 1.7 0 00-1 2.6c.9 1.5-.9 3.3-2.4 2.4a1.7 1.7 0 00-2.6 1c-.4 1.8-3 1.8-3.4 0a1.7 1.7 0 00-2.5-1c-1.6.9-3.3-.9-2.4-2.4a1.7 1.7 0 00-1.1-2.6c-1.7-.4-1.7-3 0-3.4a1.7 1.7 0 001.1-2.5c-.9-1.6.8-3.3 2.4-2.4 1 .6 2.3 0 2.5-1.1zM15 12a3 3 0 11-6 0 3 3 0 016 0z'],
        ];
    }
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title }} – Admin {{ $site->get('site_name') }}</title>
    <link rel="icon" href="{{ asset('images/logo.svg') }}" type="image/svg+xml">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-100 font-sans text-stone-800 antialiased">
    <div class="lg:flex">
        <aside class="bg-husk-950 text-husk-100 lg:fixed lg:inset-y-0 lg:w-64">
            <details class="group lg:contents" open>
                <summary class="flex items-center justify-between px-5 py-4 lg:hidden">
                    <span class="font-display text-lg font-bold text-white">{{ $site->get('site_name') }}</span>
                    <span class="text-sm">Menu</span>
                </summary>
                <div class="flex flex-col lg:h-full">
                    <a href="{{ route('admin.dashboard') }}" class="hidden items-center gap-2 px-6 py-6 lg:flex">
                        <img src="{{ asset('images/logo.svg') }}" alt="" class="size-9 rounded-full">
                        <span class="font-display text-lg font-bold leading-tight text-white">{{ $site->get('site_name') }}<span class="block text-[10px] font-sans font-semibold uppercase tracking-[0.25em] text-husk-200/70">Admin Panel</span></span>
                    </a>
                    <nav class="flex-1 space-y-1 px-3 pb-4" aria-label="Menu admin">
                        @foreach ($menu as $item)
                            <a href="{{ route($item['route']) }}" @class([
                                'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                                'bg-husk-800 text-white' => request()->routeIs($item['active']),
                                'hover:bg-husk-900 hover:text-white' => ! request()->routeIs($item['active']),
                            ])>
                                <svg class="size-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/></svg>
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </nav>
                    <div class="border-t border-husk-900 p-4 text-sm">
                        <a href="{{ route('admin.profile.edit') }}" class="block truncate font-semibold text-white hover:underline">{{ auth()->user()->name }}</a>
                        <p class="text-xs text-husk-200/70">{{ auth()->user()->role->label() }}</p>
                        <div class="mt-3 flex gap-2">
                            <a href="{{ route('home') }}" target="_blank" class="flex-1 rounded-lg bg-husk-900 px-3 py-1.5 text-center text-xs hover:bg-husk-800">Lihat Website</a>
                            <form method="POST" action="{{ route('admin.logout') }}" class="flex-1">
                                @csrf
                                <button class="w-full rounded-lg bg-husk-900 px-3 py-1.5 text-xs hover:bg-red-900">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </details>
        </aside>

        <main class="min-w-0 flex-1 lg:ml-64">
            <div class="mx-auto max-w-6xl px-4 py-8 sm:px-8">
                @if (session('status'))
                    <div class="mb-6 rounded-xl border border-husk-200 bg-husk-50 px-4 py-3 text-sm font-medium text-husk-800" role="status">{{ session('status') }}</div>
                @endif
                @if ($errors->has('user'))
                    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800" role="alert">{{ $errors->first('user') }}</div>
                @endif

                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>
