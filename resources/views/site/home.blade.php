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
        'name' => 'Daftar Harga Durian Bali',
        'itemListElement' => $products->values()->map(fn ($product, $index) => [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'url' => route('products.show', $product),
            'name' => $product->name,
        ])->all(),
    ]];

    $heroImage = $products->first(fn ($product) => $product->image_path)?->imageUrl();
    $featuredProduct = $products->firstWhere('is_featured', true) ?? $products->first();
    $lowestPrice = $products->min('price');
@endphp

<x-layouts.site
    :title="$site->get('home_meta_title')"
    :description="$site->get('home_meta_description')"
    :canonical="route('home')"
    :image="$heroImage"
    :schemas="[...$faqSchema, ...$productListSchema]"
    full-title
    transparent-header
>
    {{-- ============ HERO ============ --}}
    <section data-spotlight class="grain relative isolate overflow-hidden bg-husk-950 text-white">
        <div class="spotlight pointer-events-none absolute inset-0 -z-10"></div>
        <div class="pointer-events-none absolute -left-40 top-20 -z-10 size-[34rem] animate-blob bg-husk-600/50 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-20 -top-32 -z-10 size-[30rem] animate-blob bg-flesh-500/25 blur-3xl [animation-delay:-9s]"></div>
        <div class="pointer-events-none absolute inset-0 -z-10 bg-[radial-gradient(rgb(255_255_255/0.06)_1px,transparent_1px)] [background-size:28px_28px] [mask-image:radial-gradient(ellipse_at_center,#000_30%,transparent_75%)]"></div>

        <div class="mx-auto grid min-h-[100svh] max-w-7xl items-center gap-10 px-4 pb-24 pt-32 sm:px-6 lg:grid-cols-[1.1fr_1fr] lg:pt-28">
            <div class="relative z-10">
                <p data-reveal class="inline-flex items-center gap-2.5 rounded-full bg-white/5 py-1.5 pl-2 pr-4 text-sm font-medium text-flesh-100 ring-1 ring-white/15 backdrop-blur">
                    <span class="relative flex size-6 items-center justify-center rounded-full bg-flesh-400/20">
                        <span class="absolute inset-0 animate-ping-slow rounded-full bg-flesh-400/40"></span>
                        <span class="size-2 rounded-full bg-flesh-400"></span>
                    </span>
                    Musim durian Bali telah tiba
                </p>

                <h1 data-reveal style="--reveal-delay: 100ms" class="mt-7 font-display text-5xl font-bold leading-[1.02] tracking-tight sm:text-6xl lg:text-7xl">
                    Jual Durian Bali Asli,
                    <span class="text-gradient-gold italic">Matang Pohon</span>
                    <span class="relative inline-block">
                        &amp; Manis Legit
                        <svg class="absolute -bottom-3 left-0 w-full text-flesh-400" viewBox="0 0 300 12" fill="none" preserveAspectRatio="none" aria-hidden="true"><path d="M2 9c60-6 180-9 296-3" stroke="currentColor" stroke-width="4" stroke-linecap="round" pathLength="1" class="[stroke-dasharray:1] [stroke-dashoffset:1] [animation:draw_1.4s_.9s_cubic-bezier(.16,1,.3,1)_forwards]"/></svg>
                    </span>
                </h1>

                <p data-reveal style="--reveal-delay: 200ms" class="mt-8 max-w-xl text-lg leading-relaxed text-husk-100/80">
                    Durian asli Bali langsung dari kebun petani {{ $site->get('city') }}. Dipilih satu per satu, <strong class="font-semibold text-white">garansi ganti jika busuk</strong>, dan diantar segar ke {{ Str::before($site->get('service_area'), ',') }} hingga seluruh Bali.
                </p>

                <div data-reveal style="--reveal-delay: 300ms" class="mt-10 flex flex-wrap items-center gap-4">
                    <x-site.whatsapp-link size="lg">Pesan via WhatsApp</x-site.whatsapp-link>
                    <a href="#harga-durian" class="group inline-flex items-center gap-3 rounded-full px-6 py-4 text-lg font-semibold text-white ring-1 ring-white/25 backdrop-blur transition hover:bg-white hover:text-husk-950">
                        Lihat Harga
                        <span class="flex size-7 items-center justify-center rounded-full bg-white/10 transition group-hover:translate-y-0.5 group-hover:bg-husk-950 group-hover:text-white" aria-hidden="true">&darr;</span>
                    </a>
                </div>

                <dl data-reveal style="--reveal-delay: 400ms" class="mt-14 grid max-w-lg grid-cols-3 divide-x divide-white/10 rounded-3xl bg-white/5 py-5 ring-1 ring-white/10 backdrop-blur">
                    <div class="px-4 text-center sm:px-6">
                        <dt class="mt-1 text-xs text-husk-200/80">Durian asli Bali</dt>
                        <dd class="font-display text-3xl font-bold text-flesh-300"><span data-count-to="100" data-count-suffix="%">100%</span></dd>
                    </div>
                    <div class="px-4 text-center sm:px-6">
                        <dt class="mt-1 text-xs text-husk-200/80">Jenis durian</dt>
                        <dd class="font-display text-3xl font-bold text-flesh-300"><span data-count-to="{{ max(1, $products->count()) }}">{{ max(1, $products->count()) }}</span>+</dd>
                    </div>
                    <div class="px-4 text-center sm:px-6">
                        <dt class="mt-1 text-xs text-husk-200/80">Kab/kota se-Bali</dt>
                        <dd class="font-display text-3xl font-bold text-flesh-300"><span data-count-to="9">9</span></dd>
                    </div>
                </dl>
            </div>

            {{-- Durian stage --}}
            <div class="relative mx-auto aspect-square w-full max-w-[34rem]" data-reveal="zoom" style="--reveal-delay: 250ms">
                <div class="absolute inset-[6%] animate-spin-slow rounded-full border-2 border-dashed border-flesh-300/25"></div>
                <div class="absolute inset-[16%] animate-spin-reverse rounded-full border border-white/10"></div>
                <div class="absolute inset-[18%] rounded-full bg-gradient-to-br from-flesh-300/40 via-flesh-500/20 to-transparent blur-2xl"></div>

                <div class="absolute inset-[8%]" data-depth="-25">
                    @if ($heroImage)
                        <img src="{{ $heroImage }}" alt="Durian asli Bali matang pohon dari kebun {{ $site->get('city') }}" width="560" height="560" fetchpriority="high"
                             class="size-full animate-float rounded-full object-cover shadow-2xl shadow-black/40 ring-8 ring-white/10">
                    @else
                        <img src="{{ asset('images/durian.svg') }}" alt="Durian asli Bali matang pohon dari kebun {{ $site->get('city') }}" width="560" height="600" fetchpriority="high"
                             class="size-full animate-float object-contain drop-shadow-[0_35px_45px_rgba(0,0,0,0.45)]">
                    @endif
                </div>

                {{-- Floating pods & leaves --}}
                <img src="{{ asset('images/durian-pod.svg') }}" alt="" class="absolute left-[2%] top-[18%] w-[13%] animate-float-slow drop-shadow-xl [animation-delay:-2s]" data-depth="45">
                <img src="{{ asset('images/durian-pod.svg') }}" alt="" class="absolute bottom-[10%] right-[4%] w-[15%] rotate-[30deg] animate-float drop-shadow-xl [animation-delay:-4s]" data-depth="60">
                <img src="{{ asset('images/leaf.svg') }}" alt="" class="absolute right-[10%] top-[4%] w-[18%] rotate-[-25deg] animate-bob drop-shadow-lg" data-depth="35">
                <img src="{{ asset('images/leaf.svg') }}" alt="" class="absolute bottom-[4%] left-[14%] w-[14%] rotate-[160deg] animate-bob drop-shadow-lg [animation-delay:-1.5s]" data-depth="-30">

                {{-- Glass chips --}}
                <div class="absolute -left-2 bottom-[22%] animate-bob rounded-2xl bg-white/90 px-4 py-3 text-stone-800 shadow-2xl backdrop-blur sm:-left-6" data-depth="20">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-stone-400">Mulai dari</p>
                    <p class="font-display text-2xl font-bold text-husk-800">{{ $lowestPrice ? 'Rp '.number_format($lowestPrice, 0, ',', '.') : 'Tanya harga' }}</p>
                </div>
                <div class="absolute right-0 top-[26%] flex animate-bob items-center gap-2.5 rounded-2xl bg-husk-900/70 px-4 py-3 shadow-2xl ring-1 ring-white/15 backdrop-blur-md [animation-delay:-2s] sm:-right-4" data-depth="-20">
                    <span class="flex size-9 items-center justify-center rounded-xl bg-flesh-400 text-lg text-husk-950">✓</span>
                    <span class="text-sm font-semibold leading-tight">Matang pohon<br><span class="font-normal text-husk-200">bukan diperam</span></span>
                </div>
                <div class="absolute bottom-[-2%] right-[22%] flex animate-bob items-center gap-2 rounded-full bg-white/90 px-4 py-2 text-sm font-semibold text-husk-900 shadow-2xl [animation-delay:-3s]" data-depth="30">
                    🛵 Antar se-Bali
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
                @foreach (['Durian Asli Bali', 'Matang Pohon', 'Garansi Ganti', 'Antar se-Bali', 'Langsung dari Petani', 'Manis Legit', 'Durian Montong', 'Durian Kupas'] as $word)
                    <span class="flex items-center gap-6 px-6 font-display text-2xl font-bold text-husk-950 sm:text-3xl">
                        {{ $word }}
                        <img src="{{ asset('images/durian-pod.svg') }}" alt="" class="w-6 rotate-12">
                    </span>
                @endforeach
            @endforeach
        </div>
    </div>

    {{-- ============ KEUNGGULAN (bento) ============ --}}
    <section class="mx-auto max-w-7xl px-4 py-28 sm:px-6" aria-labelledby="keunggulan">
        <div class="max-w-3xl" data-reveal>
            <p class="text-sm font-bold uppercase tracking-[0.2em] text-flesh-500">Kenapa kami?</p>
            <h2 id="keunggulan" class="mt-3 font-display text-4xl font-bold leading-tight text-husk-950 sm:text-5xl">Kenapa Beli Durian Bali di {{ $site->get('site_name') }}?</h2>
        </div>

        <div class="mt-14 grid gap-5 md:grid-cols-6 md:grid-rows-2" data-reveal-stagger="120">
            <div data-reveal class="grain group relative overflow-hidden rounded-[2rem] bg-husk-900 p-8 text-white md:col-span-3 md:row-span-2 md:p-10">
                <img src="{{ asset('images/durian.svg') }}" alt="" class="absolute -bottom-16 -right-16 w-72 opacity-90 transition duration-1000 group-hover:-rotate-12 group-hover:scale-110 md:w-96">
                <span class="inline-flex size-14 items-center justify-center rounded-2xl bg-flesh-400 text-2xl">🌳</span>
                <h3 class="mt-6 max-w-xs font-display text-3xl font-bold leading-tight">Asli dari kebun-kebun Bali</h3>
                <p class="mt-4 max-w-xs leading-relaxed text-husk-100/80">Langsung dari petani di Tabanan, Buleleng &amp; Jembrana — bukan durian luar pulau yang dilabeli "durian Bali".</p>
            </div>
            @foreach ([
                ['emoji' => '☀️', 'title' => 'Matang Pohon', 'text' => 'Dipanen saat matang alami, aroma kuat dan daging legit.', 'class' => 'bg-flesh-100 md:col-span-3'],
                ['emoji' => '🛡️', 'title' => 'Garansi Ganti', 'text' => 'Busuk atau mentah? Kami ganti, tanpa ribet.', 'class' => 'bg-white ring-1 ring-stone-200/70 md:col-span-1 lg:col-span-1'],
                ['emoji' => '🛵', 'title' => 'Antar se-Bali', 'text' => 'Ke rumah, villa, hingga hotel Anda di Bali.', 'class' => 'bg-husk-100 md:col-span-2'],
            ] as $feature)
                <div data-reveal class="group rounded-[2rem] p-7 transition duration-500 hover:-translate-y-1.5 hover:shadow-xl hover:shadow-husk-900/10 {{ $feature['class'] }}">
                    <span class="inline-flex size-12 items-center justify-center rounded-2xl bg-white text-2xl shadow-sm transition duration-500 group-hover:-rotate-12 group-hover:scale-110">{{ $feature['emoji'] }}</span>
                    <h3 class="mt-5 font-display text-xl font-bold text-husk-950">{{ $feature['title'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-stone-600">{{ $feature['text'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ============ SHOWCASE ============ --}}
    @if ($featuredProduct)
        <section class="grain relative overflow-hidden bg-husk-950 py-28 text-white" aria-labelledby="unggulan">
            <div class="pointer-events-none absolute left-1/3 top-1/2 size-[40rem] -translate-y-1/2 animate-blob bg-flesh-500/15 blur-3xl"></div>
            <div class="relative mx-auto grid max-w-7xl items-center gap-16 px-4 sm:px-6 lg:grid-cols-2">
                <div class="relative mx-auto aspect-square w-full max-w-lg" data-reveal="left">
                    <div class="absolute inset-0 rounded-full bg-gradient-to-br from-flesh-300 to-flesh-500 opacity-90"></div>
                    <div class="absolute inset-[-6%] animate-spin-slow rounded-full border border-dashed border-flesh-300/40"></div>
                    <div class="absolute inset-0" data-parallax="0.08">
                        <img src="{{ $featuredProduct->imageUrl() ?? asset('images/durian.svg') }}" alt="{{ $featuredProduct->name }} asli Bali" width="520" height="520" loading="lazy"
                             @class(['size-full animate-float', 'rounded-full object-cover p-3' => $featuredProduct->imageUrl(), 'scale-110 object-contain drop-shadow-[0_40px_40px_rgba(0,0,0,0.5)]' => ! $featuredProduct->imageUrl()])>
                    </div>
                    <span class="absolute -right-2 top-8 flex size-28 rotate-12 flex-col items-center justify-center rounded-full bg-husk-950 text-center ring-4 ring-flesh-400 sm:size-32">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-flesh-300">Hanya</span>
                        <span class="font-display text-lg font-bold leading-none sm:text-xl">{{ $featuredProduct->formattedPrice() }}</span>
                        <span class="text-[10px] text-husk-200">{{ $featuredProduct->price_unit }}</span>
                    </span>
                </div>

                <div data-reveal="right">
                    <p class="text-sm font-bold uppercase tracking-[0.2em] text-flesh-300">★ Paling dicari</p>
                    <h2 id="unggulan" class="mt-3 font-display text-4xl font-bold leading-tight sm:text-6xl">{{ $featuredProduct->name }}</h2>
                    <p class="mt-6 text-lg leading-relaxed text-husk-100/80">{{ $featuredProduct->short_description }}</p>

                    <div class="mt-10 space-y-5" data-reveal>
                        <p class="text-sm font-semibold text-husk-200">Profil rasa</p>
                        @foreach (['Manis' => 0.9, 'Legit / creamy' => 0.85, 'Aroma' => 0.95, 'Pahit' => 0.3] as $label => $value)
                            <div>
                                <div class="flex justify-between text-sm"><span>{{ $label }}</span><span class="text-husk-200">{{ $value * 10 }}/10</span></div>
                                <div class="mt-2 h-2.5 overflow-hidden rounded-full bg-white/10">
                                    <div class="meter-bar h-full rounded-full bg-gradient-to-r from-flesh-300 to-flesh-500" style="--value: {{ $value }}; --reveal-delay: {{ $loop->index * 150 + 200 }}ms"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-10 flex flex-wrap gap-4">
                        <x-site.whatsapp-link :product="$featuredProduct" size="lg">Pesan Sekarang</x-site.whatsapp-link>
                        <a href="{{ route('products.show', $featuredProduct) }}" class="inline-flex items-center rounded-full px-6 py-4 font-semibold ring-1 ring-white/25 transition hover:bg-white hover:text-husk-950">Lihat detail</a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ============ PRODUK ============ --}}
    <section id="harga-durian" class="scroll-mt-20 py-28" aria-labelledby="harga-durian-title">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="flex flex-wrap items-end justify-between gap-6" data-reveal>
                <div class="max-w-2xl">
                    <p class="text-sm font-bold uppercase tracking-[0.2em] text-flesh-500">Daftar harga</p>
                    <h2 id="harga-durian-title" class="mt-3 font-display text-4xl font-bold leading-tight text-husk-950 sm:text-5xl">Harga Durian Bali Hari Ini</h2>
                    <p class="mt-4 text-lg text-stone-600">Harga dapat berubah mengikuti musim panen — konfirmasi stok via WhatsApp.</p>
                </div>
                <a href="{{ route('products.index') }}" class="group inline-flex items-center gap-2 rounded-full bg-husk-950 px-6 py-3 font-semibold text-white transition hover:bg-husk-800">
                    Semua jenis durian <span class="transition group-hover:translate-x-1" aria-hidden="true">&rarr;</span>
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
                <h2 id="cara-pesan" class="mt-3 font-display text-4xl font-bold text-husk-950 sm:text-5xl">Cara Pesan Durian Bali Online</h2>
            </div>
            <ol class="relative mt-16 grid gap-10 md:grid-cols-3" data-reveal-stagger="200">
                <span class="absolute left-[16%] right-[16%] top-8 hidden h-0.5 bg-[repeating-linear-gradient(90deg,var(--color-flesh-400)_0_10px,transparent_10px_18px)] md:block" aria-hidden="true"></span>
                @foreach ([
                    ['emoji' => '🛒', 'title' => 'Pilih Durian', 'text' => 'Lihat daftar harga dan pilih jenis durian Bali yang Anda inginkan.'],
                    ['emoji' => '💬', 'title' => 'Chat WhatsApp', 'text' => 'Klik "Pesan via WhatsApp", sebutkan jumlah dan alamat pengiriman.'],
                    ['emoji' => '🎉', 'title' => 'Durian Diantar', 'text' => 'Durian dicek kualitasnya, lalu diantar segar ke lokasi Anda.'],
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

    {{-- ============ TESTIMONI ============ --}}
    @if ($testimonials->isNotEmpty())
        <section class="py-28" aria-labelledby="testimoni">
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
    @else
        <div class="py-14"></div>
    @endif

    {{-- ============ SEO COPY ============ --}}
    <section class="mx-auto grid max-w-7xl items-center gap-14 px-4 sm:px-6 lg:grid-cols-[1fr_1.2fr]" aria-labelledby="tentang-durian-bali">
        <div class="relative mx-auto aspect-square w-full max-w-md" data-reveal="left">
            <div class="absolute inset-0 animate-blob bg-gradient-to-br from-husk-200 to-flesh-200"></div>
            <img src="{{ asset('images/durian.svg') }}" alt="Durian lokal Bali terbelah dengan daging buah kuning" width="420" height="450" loading="lazy" class="relative size-full animate-float-slow object-contain p-10 drop-shadow-2xl">
        </div>
        <div data-reveal="right">
            <p class="text-sm font-bold uppercase tracking-[0.2em] text-flesh-500">Mengenal durian Bali</p>
            <h2 id="tentang-durian-bali" class="mt-3 font-display text-4xl font-bold leading-tight text-husk-950">Durian Bali: Oleh-oleh &amp; Buah Favorit dari Pulau Dewata</h2>
            <div class="prose-durian mt-6 text-lg">
                <p>Bali bukan hanya terkenal dengan pantai dan budayanya. Daerah dataran tinggi seperti <strong>Pupuan dan Penebel di Tabanan</strong>, <strong>Munduk dan Sukasada di Buleleng</strong>, serta <strong>Jembrana</strong> menghasilkan durian lokal dengan karakter rasa yang khas: manis, sedikit pahit, dengan aroma yang kuat.</p>
                <p>Di {{ $site->get('site_name') }}, kami menjual <strong>durian asli Bali</strong> yang dipetik matang dari kebun petani, mulai dari <a href="{{ route('products.index') }}">durian lokal Bali, durian Montong Bali, hingga durian kupas</a> yang siap santap.</p>
                <p>Ingin tahu cara memilih durian yang benar-benar matang? Baca panduan lengkap di <a href="{{ route('articles.index') }}">artikel kami</a>.</p>
            </div>
        </div>
    </section>

    {{-- ============ ARTIKEL ============ --}}
    @if ($articles->isNotEmpty())
        <section class="mx-auto max-w-7xl px-4 pt-28 sm:px-6" aria-labelledby="artikel-terbaru">
            <div class="flex flex-wrap items-end justify-between gap-4" data-reveal>
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.2em] text-flesh-500">Blog</p>
                    <h2 id="artikel-terbaru" class="mt-3 font-display text-4xl font-bold text-husk-950 sm:text-5xl">Tips &amp; Info Durian</h2>
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
                <h2 id="faq" class="mt-3 font-display text-4xl font-bold leading-tight text-husk-950 sm:text-5xl">Pertanyaan Seputar Durian Bali</h2>
                <p class="mt-4 text-stone-600">Belum menemukan jawaban? Tanyakan langsung ke kami.</p>
                <x-site.whatsapp-link class="mt-8">Tanya via WhatsApp</x-site.whatsapp-link>
            </div>
            <x-site.faq-list :faqs="$faqs" />
        </section>
    @endif

    <x-site.cta-band />
</x-layouts.site>
