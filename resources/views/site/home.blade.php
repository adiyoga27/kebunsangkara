@php
    $faqSchema = $faqs->isEmpty() ? [] : [[
        '@type' => 'FAQPage',
        'mainEntity' => $faqs->map(fn ($faq) => [
            '@type' => 'Question',
            'name' => $faq->question,
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq->answer],
        ])->all(),
    ]];

    $productListSchema = $products->isEmpty() ? [] : [[
        '@type' => 'ItemList',
        'name' => 'Daftar Harga Buah Kebun Sangkara',
        'itemListElement' => $products->values()->map(fn ($product, $index) => [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'url' => route('products.show', $product),
            'name' => $product->name,
        ])->all(),
    ]];

    $featuredProduct = $products->firstWhere('is_featured', true) ?? $products->first();
    $lowestPrice = $products->min('price');
    $categories = App\Enums\FruitCategory::cases();
@endphp

<x-layouts.site
    :title="$site->get('home_meta_title')"
    :description="$site->get('home_meta_description')"
    :canonical="route('home')"
    :image="asset('images/kebun/durian-matang-di-pohon.webp')"
    :schemas="[...$faqSchema, ...$productListSchema]"
    full-title
    transparent-header
>
    {{-- ============ HERO ============ --}}
    <section data-spotlight class="grain relative isolate overflow-hidden bg-husk-950 text-white">
        <div class="absolute inset-0 -z-20">
            <img src="{{ asset('images/kebun/kebun-alpukat.webp') }}" alt="" class="size-full scale-110 object-cover opacity-25 blur-[2px]" data-parallax="-0.15">
            <div class="absolute inset-0 bg-gradient-to-b from-husk-950/70 via-husk-950/80 to-husk-950"></div>
        </div>
        <div class="spotlight pointer-events-none absolute inset-0 -z-10"></div>
        <div class="pointer-events-none absolute -right-20 -top-32 -z-10 size-[30rem] animate-blob bg-flesh-500/20 blur-3xl"></div>

        <div class="mx-auto grid min-h-[100svh] max-w-7xl items-center gap-14 px-4 pb-24 pt-32 sm:px-6 lg:grid-cols-[1.05fr_1fr] lg:pt-28">
            <div class="relative z-10">
                <p data-reveal class="inline-flex items-center gap-2.5 rounded-full bg-white/5 py-1.5 pl-2 pr-4 text-sm font-medium text-flesh-100 ring-1 ring-white/15 backdrop-blur">
                    <span class="relative flex size-6 items-center justify-center rounded-full bg-flesh-400/20">
                        <span class="absolute inset-0 animate-ping-slow rounded-full bg-flesh-400/40"></span>
                        <span class="size-2 rounded-full bg-flesh-400"></span>
                    </span>
                    Panen segar dari kebun Bali
                </p>

                <h1 data-reveal style="--reveal-delay: 100ms" class="mt-7 font-display text-5xl font-bold leading-[1.02] tracking-tight sm:text-6xl lg:text-7xl">
                    Buah Segar <span class="text-gradient-gold italic">Langsung</span>
                    <span class="relative inline-block">
                        dari Kebun Bali
                        <svg class="absolute -bottom-3 left-0 w-full text-flesh-400" viewBox="0 0 300 12" fill="none" preserveAspectRatio="none" aria-hidden="true"><path d="M2 9c60-6 180-9 296-3" stroke="currentColor" stroke-width="4" stroke-linecap="round" pathLength="1" class="[stroke-dasharray:1] [stroke-dashoffset:1] [animation:draw_1.4s_.9s_cubic-bezier(.16,1,.3,1)_forwards]"/></svg>
                    </span>
                </h1>

                <p data-reveal style="--reveal-delay: 200ms" class="mt-8 max-w-xl text-lg leading-relaxed text-husk-100/80">
                    <strong class="font-semibold text-white">{{ $site->get('site_name') }}</strong> menjual durian, alpukat, jeruk, dan manggis yang dipetik matang dari kebun — tanpa perantara, diantar segar ke {{ Str::before($site->get('service_area'), ',') }} hingga seluruh Bali.
                </p>

                <div data-reveal style="--reveal-delay: 300ms" class="mt-10 flex flex-wrap items-center gap-4">
                    <x-site.whatsapp-link size="lg">Pesan via WhatsApp</x-site.whatsapp-link>
                    <a href="#kategori" class="group inline-flex items-center gap-3 rounded-full px-6 py-4 text-lg font-semibold text-white ring-1 ring-white/25 backdrop-blur transition hover:bg-white hover:text-husk-950">
                        Lihat Buah
                        <span class="flex size-7 items-center justify-center rounded-full bg-white/10 transition group-hover:translate-y-0.5 group-hover:bg-husk-950 group-hover:text-white" aria-hidden="true">&darr;</span>
                    </a>
                </div>

                <div data-reveal style="--reveal-delay: 400ms" class="mt-12 flex flex-wrap gap-2">
                    @foreach ($categories as $fruit)
                        <a href="{{ route('products.category', $fruit) }}" class="rounded-full bg-white/10 px-4 py-2 text-sm font-semibold ring-1 ring-white/15 backdrop-blur transition hover:-translate-y-0.5 hover:bg-flesh-400 hover:text-husk-950">
                            {{ $fruit->emoji() }} {{ $fruit->label() }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Photo collage --}}
            <div class="relative mx-auto aspect-[4/4.4] w-full max-w-[32rem]" data-reveal="zoom" style="--reveal-delay: 250ms">
                <div class="absolute -inset-6 animate-spin-slow rounded-full border-2 border-dashed border-flesh-300/20"></div>

                <div class="absolute inset-x-[8%] inset-y-0 overflow-hidden rounded-t-[999px] rounded-b-[3rem] shadow-2xl shadow-black/50 ring-8 ring-white/10" data-depth="-15">
                    <img src="{{ asset('images/kebun/durian-matang-di-pohon.webp') }}" alt="Durian matang di pohon di kebun {{ $site->get('site_name') }} Bali" width="680" height="451" fetchpriority="high"
                         class="size-full scale-110 object-cover" data-parallax="0.05">
                    <div class="absolute inset-0 bg-gradient-to-t from-husk-950/60 via-transparent to-transparent"></div>
                </div>

                <figure class="absolute -left-4 top-[14%] w-[34%] animate-bob overflow-hidden rounded-3xl bg-white p-1.5 shadow-2xl [animation-delay:-1s] sm:-left-8" data-depth="40">
                    <img src="{{ asset('images/kebun/alpukat-di-pohon.webp') }}" alt="Alpukat segar di pohon" width="300" height="200" class="aspect-square w-full rounded-[1.2rem] object-cover">
                    <figcaption class="px-2 py-1.5 text-center text-xs font-bold text-husk-900">🥑 Alpukat</figcaption>
                </figure>
                <figure class="absolute -right-2 top-[40%] w-[32%] animate-bob overflow-hidden rounded-3xl bg-white p-1.5 shadow-2xl [animation-delay:-2.5s] sm:-right-8" data-depth="-35">
                    <img src="{{ asset('images/kebun/buah-manggis.webp') }}" alt="Buah manggis segar" width="300" height="225" class="aspect-square w-full rounded-[1.2rem] object-cover">
                    <figcaption class="px-2 py-1.5 text-center text-xs font-bold text-husk-900">🟣 Manggis</figcaption>
                </figure>
                <figure class="absolute -bottom-6 left-[6%] w-[30%] animate-bob overflow-hidden rounded-3xl bg-white p-1.5 shadow-2xl [animation-delay:-4s]" data-depth="50">
                    <img src="{{ asset('images/kebun/petani-memetik-jeruk.webp') }}" alt="Memetik jeruk di kebun" width="300" height="200" class="aspect-square w-full rounded-[1.2rem] object-cover object-[40%_center]">
                    <figcaption class="px-2 py-1.5 text-center text-xs font-bold text-husk-900">🍊 Jeruk</figcaption>
                </figure>

                <div class="absolute -bottom-4 right-[4%] animate-bob rounded-2xl bg-husk-900/80 px-4 py-3 shadow-2xl ring-1 ring-white/15 backdrop-blur-md [animation-delay:-3s]" data-depth="25">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-husk-200">Mulai dari</p>
                    <p class="font-display text-2xl font-bold text-flesh-300">{{ $lowestPrice ? 'Rp '.number_format($lowestPrice, 0, ',', '.') : 'Tanya harga' }}</p>
                </div>
            </div>
        </div>

        <a href="#marquee" class="absolute bottom-6 left-1/2 hidden -translate-x-1/2 flex-col items-center gap-2 text-xs font-medium uppercase tracking-[0.25em] text-husk-200/60 lg:flex" aria-label="Gulir ke bawah">
            Scroll
            <span class="flex h-10 w-6 justify-center rounded-full ring-1 ring-white/25"><span class="mt-2 size-1.5 animate-bounce rounded-full bg-flesh-300"></span></span>
        </a>
    </section>

    {{-- ============ MARQUEE ============ --}}
    <div id="marquee" class="relative z-10 -mt-6 -rotate-2 overflow-hidden bg-flesh-400 py-5 shadow-xl shadow-flesh-500/20" aria-hidden="true">
        <div class="flex w-max animate-marquee">
            @foreach (range(1, 2) as $copy)
                @foreach (['🍈 Durian Matang Pohon', '🥑 Alpukat Mentega', '🍊 Jeruk Segar', '🟣 Manggis Manis', '🌿 Langsung dari Kebun', '🛵 Antar se-Bali', '🛡️ Garansi Ganti'] as $word)
                    <span class="flex items-center gap-6 px-6 font-display text-2xl font-bold text-husk-950 sm:text-3xl">
                        {{ $word }} <span class="text-husk-950/30">✦</span>
                    </span>
                @endforeach
            @endforeach
        </div>
    </div>

    {{-- ============ KATEGORI ============ --}}
    <section id="kategori" class="mx-auto max-w-7xl scroll-mt-20 px-4 py-28 sm:px-6" aria-labelledby="kategori-title">
        <div class="flex flex-wrap items-end justify-between gap-6" data-reveal>
            <div class="max-w-2xl">
                <p class="text-sm font-bold uppercase tracking-[0.2em] text-flesh-500">Hasil kebun kami</p>
                <h2 id="kategori-title" class="mt-3 font-display text-4xl font-bold leading-tight text-husk-950 sm:text-5xl">Pilih Buah Favorit Anda</h2>
            </div>
            <p class="max-w-sm text-stone-600">Empat jenis buah unggulan yang kami tanam dan rawat sendiri di kebun Bali.</p>
        </div>

        <div class="mt-14 grid gap-5 sm:grid-cols-2 lg:grid-cols-4" data-reveal-stagger="120">
            @foreach ($categories as $fruit)
                <a data-reveal href="{{ route('products.category', $fruit) }}" @class([
                    'group relative isolate flex aspect-[3/4] flex-col justify-end overflow-hidden rounded-[2rem] p-6 text-white shadow-lg transition duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-husk-900/30',
                    'lg:translate-y-10' => $loop->odd,
                ])>
                    <img src="{{ asset($fruit->imagePath()) }}" alt="{{ $fruit->label() }} segar dari kebun Bali" width="400" height="530" loading="lazy"
                         class="absolute inset-0 -z-10 size-full object-cover transition duration-1000 ease-out group-hover:scale-110">
                    <span class="absolute inset-0 -z-10 bg-gradient-to-t from-husk-950/95 via-husk-950/30 to-transparent transition duration-500 group-hover:from-husk-950"></span>
                    <span class="absolute right-5 top-5 flex size-12 items-center justify-center rounded-full bg-white/15 text-xl ring-1 ring-white/30 backdrop-blur transition duration-500 group-hover:rotate-[-45deg] group-hover:bg-flesh-400 group-hover:text-husk-950">&rarr;</span>
                    <span class="text-3xl">{{ $fruit->emoji() }}</span>
                    <h3 class="mt-2 font-display text-3xl font-bold">{{ $fruit->label() }}</h3>
                    <p class="mt-1 text-sm text-husk-100/85">{{ $fruit->tagline() }}</p>
                    <p class="mt-4 inline-flex w-fit rounded-full bg-white/15 px-3 py-1 text-xs font-semibold backdrop-blur">
                        {{ ($categoryCounts[$fruit->value] ?? 0) > 0 ? $categoryCounts[$fruit->value].' produk tersedia' : 'Tanya stok' }}
                    </p>
                </a>
            @endforeach
        </div>
    </section>

    {{-- ============ KEBUN STORY ============ --}}
    <section class="grain relative overflow-hidden bg-husk-950 py-28 text-white" aria-labelledby="kebun-kami">
        <div class="pointer-events-none absolute left-1/3 top-1/2 size-[40rem] -translate-y-1/2 animate-blob bg-husk-600/30 blur-3xl"></div>
        <div class="relative mx-auto grid max-w-7xl items-center gap-16 px-4 sm:px-6 lg:grid-cols-2">
            <div class="relative h-[28rem] sm:h-[34rem]" data-reveal="left">
                <div class="absolute left-0 top-0 h-[78%] w-[72%] overflow-hidden rounded-[2.5rem] shadow-2xl">
                    <img src="{{ asset('images/kebun/kebun-alpukat.webp') }}" alt="Barisan pohon alpukat di kebun {{ $site->get('site_name') }}" width="771" height="432" loading="lazy" class="size-full scale-125 object-cover" data-parallax="0.06">
                </div>
                <div class="absolute bottom-0 right-0 h-[58%] w-[58%] overflow-hidden rounded-[2.5rem] shadow-2xl ring-8 ring-husk-950">
                    <img src="{{ asset('images/kebun/pohon-durian.webp') }}" alt="Pohon durian berbuah lebat di kebun" width="678" height="452" loading="lazy" class="size-full scale-125 object-cover" data-parallax="-0.06">
                </div>
                <div class="absolute bottom-[12%] left-[4%] flex animate-bob items-center gap-3 rounded-2xl bg-white px-4 py-3 text-stone-800 shadow-2xl">
                    <span class="flex size-10 items-center justify-center rounded-xl bg-husk-100 text-xl">🌿</span>
                    <span class="text-sm font-semibold leading-tight">Ditanam &amp; dirawat<br><span class="font-normal text-stone-500">sendiri oleh kami</span></span>
                </div>
            </div>

            <div data-reveal="right">
                <p class="text-sm font-bold uppercase tracking-[0.2em] text-flesh-300">Dari kebun ke meja Anda</p>
                <h2 id="kebun-kami" class="mt-3 font-display text-4xl font-bold leading-tight sm:text-5xl">Kenapa beli buah di {{ $site->get('site_name') }}?</h2>
                <p class="mt-6 text-lg leading-relaxed text-husk-100/80">Kami bukan sekadar reseller. Buah dipetik dari kebun kami sendiri saat waktunya tepat, lalu langsung dikirim — sehingga lebih segar dan harganya tetap bersahabat.</p>

                <div class="mt-10 grid gap-4 sm:grid-cols-2" data-reveal-stagger="100">
                    @foreach ([
                        ['☀️', 'Matang alami', 'Dipanen saat matang di pohon, bukan diperam.'],
                        ['🤝', 'Tanpa perantara', 'Langsung dari kebun, harga lebih bersahabat.'],
                        ['🛡️', 'Garansi ganti', 'Buah busuk atau rusak? Kami ganti.'],
                        ['🛵', 'Antar se-Bali', 'Ke rumah, villa, hingga hotel Anda.'],
                    ] as [$icon, $title, $text])
                        <div data-reveal class="group rounded-3xl bg-white/5 p-5 ring-1 ring-white/10 transition duration-500 hover:-translate-y-1 hover:bg-white/10">
                            <span class="inline-flex size-11 items-center justify-center rounded-2xl bg-flesh-400 text-xl transition duration-500 group-hover:-rotate-12 group-hover:scale-110">{{ $icon }}</span>
                            <h3 class="mt-4 font-display text-lg font-bold">{{ $title }}</h3>
                            <p class="mt-1 text-sm text-husk-200/80">{{ $text }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ============ PRODUK ============ --}}
    <section id="harga" class="scroll-mt-20 py-28" aria-labelledby="harga-title">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="flex flex-wrap items-end justify-between gap-6" data-reveal>
                <div class="max-w-2xl">
                    <p class="text-sm font-bold uppercase tracking-[0.2em] text-flesh-500">Daftar harga</p>
                    <h2 id="harga-title" class="mt-3 font-display text-4xl font-bold leading-tight text-husk-950 sm:text-5xl">Harga Buah Hari Ini</h2>
                    <p class="mt-4 text-lg text-stone-600">Harga mengikuti musim panen — konfirmasi stok terbaru via WhatsApp.</p>
                </div>
                <a href="{{ route('products.index') }}" class="group inline-flex items-center gap-2 rounded-full bg-husk-950 px-6 py-3 font-semibold text-white transition hover:bg-husk-800">
                    Semua produk <span class="transition group-hover:translate-x-1" aria-hidden="true">&rarr;</span>
                </a>
            </div>
            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3" data-reveal-stagger="120">
                @forelse ($products as $product)
                    <div data-reveal><x-site.product-card :product="$product" class="h-full" /></div>
                @empty
                    <p class="text-stone-500">Produk segera hadir. Hubungi kami via WhatsApp untuk info stok terbaru.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ============ CARA PESAN ============ --}}
    <section class="mx-auto max-w-7xl px-4 sm:px-6" aria-labelledby="cara-pesan">
        <div class="rounded-[2.5rem] bg-white px-6 py-20 ring-1 ring-stone-200/70 sm:px-12">
            <div class="text-center" data-reveal>
                <p class="text-sm font-bold uppercase tracking-[0.2em] text-flesh-500">Gampang banget</p>
                <h2 id="cara-pesan" class="mt-3 font-display text-4xl font-bold text-husk-950 sm:text-5xl">Cara Pesan Buah Online</h2>
            </div>
            <ol class="relative mt-16 grid gap-10 md:grid-cols-3" data-reveal-stagger="200">
                <span class="absolute left-[16%] right-[16%] top-8 hidden h-0.5 bg-[repeating-linear-gradient(90deg,var(--color-flesh-400)_0_10px,transparent_10px_18px)] md:block" aria-hidden="true"></span>
                @foreach ([
                    ['emoji' => '🧺', 'title' => 'Pilih Buah', 'text' => 'Lihat daftar harga dan pilih buah yang Anda inginkan.'],
                    ['emoji' => '💬', 'title' => 'Chat WhatsApp', 'text' => 'Klik "Pesan via WhatsApp", sebutkan jumlah dan alamat pengiriman.'],
                    ['emoji' => '🎉', 'title' => 'Buah Diantar', 'text' => 'Buah dipetik, dicek kualitasnya, lalu diantar segar ke lokasi Anda.'],
                ] as $step)
                    <li data-reveal class="group relative text-center">
                        <span class="relative mx-auto flex size-16 items-center justify-center rounded-2xl bg-husk-950 text-3xl shadow-xl shadow-husk-950/20 transition duration-500 group-hover:-translate-y-2 group-hover:rotate-6">
                            {{ $step['emoji'] }}
                            <span class="absolute -right-2 -top-2 flex size-7 items-center justify-center rounded-full bg-flesh-400 font-display text-sm font-bold text-husk-950">{{ $loop->iteration }}</span>
                        </span>
                        <h3 class="mt-6 font-display text-xl font-bold text-husk-950">{{ $step['title'] }}</h3>
                        <p class="mx-auto mt-2 max-w-xs text-stone-600">{{ $step['text'] }}</p>
                    </li>
                @endforeach
            </ol>
            <div class="mt-14 text-center" data-reveal><x-site.whatsapp-link size="lg">Mulai Pesan Sekarang</x-site.whatsapp-link></div>
        </div>
    </section>

    {{-- ============ PANEN / GALERI ============ --}}
    <section class="py-28" aria-labelledby="galeri">
        <div class="mx-auto max-w-7xl px-4 sm:px-6" data-reveal>
            <p class="text-sm font-bold uppercase tracking-[0.2em] text-flesh-500">Suasana kebun</p>
            <h2 id="galeri" class="mt-3 font-display text-4xl font-bold text-husk-950 sm:text-5xl">Intip Kebun Kami</h2>
        </div>
        <div class="mask-fade-x mt-12 overflow-hidden">
            <div class="flex w-max animate-marquee-slow gap-5 hover:[animation-play-state:paused]">
                @foreach (range(1, 2) as $copy)
                    @foreach ([
                        ['images/kebun/pohon-durian.webp', 'Pohon durian berbuah lebat'],
                        ['images/kebun/alpukat-di-pohon.webp', 'Alpukat menggantung di pohon'],
                        ['images/kebun/petani-memetik-jeruk.webp', 'Memetik jeruk di kebun'],
                        ['images/kebun/buah-manggis.webp', 'Manggis hasil panen'],
                        ['images/kebun/kebun-alpukat.webp', 'Barisan pohon alpukat'],
                        ['images/kebun/durian-matang-di-pohon.webp', 'Durian matang di pohon'],
                    ] as [$src, $caption])
                        <figure class="group relative h-72 w-64 shrink-0 overflow-hidden rounded-[2rem] even:mt-10 sm:h-80 sm:w-72" @if ($copy === 2) aria-hidden="true" @endif>
                            <img src="{{ asset($src) }}" alt="{{ $copy === 1 ? $caption : '' }}" loading="lazy" width="288" height="320" class="size-full object-cover transition duration-700 group-hover:scale-110">
                            <figcaption class="absolute inset-x-3 bottom-3 translate-y-2 rounded-2xl bg-white/90 px-4 py-2 text-sm font-semibold text-husk-900 opacity-0 backdrop-blur transition duration-500 group-hover:translate-y-0 group-hover:opacity-100">{{ $caption }}</figcaption>
                        </figure>
                    @endforeach
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ TESTIMONI ============ --}}
    @if ($testimonials->isNotEmpty())
        <section class="pb-28" aria-labelledby="testimoni">
            <div class="mx-auto max-w-7xl px-4 sm:px-6" data-reveal>
                <p class="text-sm font-bold uppercase tracking-[0.2em] text-flesh-500">Testimoni</p>
                <h2 id="testimoni" class="mt-3 font-display text-4xl font-bold text-husk-950 sm:text-5xl">Kata Pelanggan Kami</h2>
            </div>
            <div class="mask-fade-x mt-12 overflow-hidden">
                <div class="flex w-max animate-marquee-slow gap-6 hover:[animation-play-state:paused]">
                    @foreach (range(1, 2) as $copy)
                        @foreach ($testimonials as $testimonial)
                            <figure class="flex w-80 shrink-0 flex-col rounded-[2rem] bg-white p-7 ring-1 ring-stone-200/70 sm:w-96" @if ($copy === 2) aria-hidden="true" @endif>
                                <div class="text-lg tracking-widest text-flesh-400" aria-label="Rating {{ $testimonial->rating }} dari 5">{{ str_repeat('★', $testimonial->rating) }}<span class="text-stone-200">{{ str_repeat('★', 5 - $testimonial->rating) }}</span></div>
                                <blockquote class="mt-4 flex-1 text-lg leading-relaxed text-stone-700">“{{ $testimonial->content }}”</blockquote>
                                <figcaption class="mt-6 flex items-center gap-3 text-sm">
                                    <span class="flex size-10 items-center justify-center rounded-full bg-gradient-to-br from-husk-500 to-husk-800 font-bold text-white">{{ Str::upper(Str::substr($testimonial->name, 0, 1)) }}</span>
                                    <span><strong class="block text-stone-900">{{ $testimonial->name }}</strong>@if ($testimonial->location)<span class="text-stone-500">{{ $testimonial->location }}</span>@endif</span>
                                </figcaption>
                            </figure>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============ SEO COPY ============ --}}
    <section class="mx-auto max-w-4xl px-4 sm:px-6" aria-labelledby="tentang-kebun">
        <div data-reveal>
            <p class="text-sm font-bold uppercase tracking-[0.2em] text-flesh-500">Tentang {{ $site->get('site_name') }}</p>
            <h2 id="tentang-kebun" class="mt-3 font-display text-4xl font-bold leading-tight text-husk-950">Kebun Buah di Bali: Durian, Alpukat, Jeruk &amp; Manggis</h2>
            <div class="prose-kebun mt-6 text-lg">
                <p><strong>{{ $site->get('site_name') }}</strong> adalah kebun buah di Bali yang menjual hasil panennya langsung kepada pelanggan. Kami menanam <a href="{{ route('products.category', App\Enums\FruitCategory::Durian) }}">durian</a>, <a href="{{ route('products.category', App\Enums\FruitCategory::Alpukat) }}">alpukat</a>, <a href="{{ route('products.category', App\Enums\FruitCategory::Jeruk) }}">jeruk</a>, dan <a href="{{ route('products.category', App\Enums\FruitCategory::Manggis) }}">manggis</a> di tanah Bali yang subur.</p>
                <p>Karena dijual langsung dari kebun, buah yang Anda terima lebih segar dan dipetik pada waktu yang tepat. Cocok untuk konsumsi keluarga, oleh-oleh, hingga kebutuhan villa, hotel, dan kafe di Bali.</p>
                <p>Ingin tahu cara memilih buah yang benar-benar matang? Baca panduan lengkap di <a href="{{ route('articles.index') }}">artikel kami</a>.</p>
            </div>
        </div>
    </section>

    {{-- ============ ARTIKEL ============ --}}
    @if ($articles->isNotEmpty())
        <section class="mx-auto max-w-7xl px-4 pt-28 sm:px-6" aria-labelledby="artikel-terbaru">
            <div class="flex flex-wrap items-end justify-between gap-4" data-reveal>
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.2em] text-flesh-500">Blog</p>
                    <h2 id="artikel-terbaru" class="mt-3 font-display text-4xl font-bold text-husk-950 sm:text-5xl">Tips &amp; Info Buah</h2>
                </div>
                <a href="{{ route('articles.index') }}" class="group inline-flex items-center gap-2 font-semibold text-husk-700">Semua artikel <span class="transition group-hover:translate-x-1" aria-hidden="true">&rarr;</span></a>
            </div>
            <div class="mt-12 grid gap-6 md:grid-cols-3" data-reveal-stagger="120">
                @foreach ($articles as $article)
                    <div data-reveal><x-site.article-card :article="$article" /></div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ============ FAQ ============ --}}
    @if ($faqs->isNotEmpty())
        <section class="mx-auto grid max-w-7xl gap-12 px-4 pt-28 sm:px-6 lg:grid-cols-[1fr_1.6fr]" aria-labelledby="faq">
            <div data-reveal class="lg:sticky lg:top-28 lg:self-start">
                <p class="text-sm font-bold uppercase tracking-[0.2em] text-flesh-500">FAQ</p>
                <h2 id="faq" class="mt-3 font-display text-4xl font-bold leading-tight text-husk-950 sm:text-5xl">Pertanyaan yang Sering Ditanyakan</h2>
                <p class="mt-4 text-stone-600">Belum menemukan jawaban? Tanyakan langsung ke kami.</p>
                <x-site.whatsapp-link class="mt-8">Tanya via WhatsApp</x-site.whatsapp-link>
            </div>
            <x-site.faq-list :faqs="$faqs" />
        </section>
    @endif

    <x-site.cta-band />
</x-layouts.site>
