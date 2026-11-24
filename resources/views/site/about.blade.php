<x-layouts.site
    :title="__('Tentang Kami & Kontak – Kebun Buah di Bali')"
    :description="__('Kenali :site, kebun buah di :city, Bali yang menjual durian, alpukat & manggis langsung dari kebun ke pelanggan.', ['site' => $site->get('site_name'), 'city' => $site->get('city')])"
    :canonical="route('about')"
>
    <x-site.page-hero :title="__('Tentang :name', ['name' => $site->get('site_name')])" :eyebrow="__('Cerita kami')" image="images/kebun/kebun-alpukat.webp"
        :breadcrumbs="[['label' => __('Beranda'), 'url' => route('home')], ['label' => __('Tentang Kami'), 'url' => null]]">
        {{ $site->get('tagline') }}.
    </x-site.page-hero>

    <div class="mx-auto max-w-3xl px-4 pt-16 sm:px-6">
        <div data-reveal class="prose-kebun text-lg">
            {{ App\Support\SiteMarkdown::render($site->get('about_content')) }}
        </div>

        <div data-reveal class="mt-12 grid grid-cols-3 gap-3">
            @foreach (['images/kebun/pohon-durian.webp', 'images/kebun/alpukat-di-pohon.webp', 'images/kebun/buah-manggis.webp'] as $photo)
                <img src="{{ asset($photo) }}" alt="{{ __('Hasil kebun :site', ['site' => $site->get('site_name')]) }}" loading="lazy" class="aspect-square w-full rounded-3xl object-cover transition duration-500 odd:translate-y-4 hover:scale-105">
            @endforeach
        </div>

        <div class="mt-16 grid gap-4 sm:grid-cols-3" data-reveal-stagger="120">
            @foreach ([__('Kebun sendiri') => __('Ditanam & dirawat'), __('Dipilih manual') => __('Satu per satu'), __('Garansi') => __('Ganti jika rusak')] as $title => $text)
                <div data-reveal class="rounded-[2rem] bg-husk-950 p-7 text-center text-white transition duration-500 hover:-translate-y-1.5">
                    <p class="font-display text-xl font-bold text-flesh-300">{{ $title }}</p>
                    <p class="mt-1 text-sm text-husk-200">{{ $text }}</p>
                </div>
            @endforeach
        </div>

        @if ($testimonials->isNotEmpty())
            <h2 data-reveal class="mt-20 font-display text-4xl font-bold text-husk-950">{{ __('Apa Kata Pelanggan') }}</h2>
            <div class="mt-6 space-y-4">
                @foreach ($testimonials as $testimonial)
                    <figure data-reveal class="rounded-[2rem] bg-white p-7 ring-1 ring-stone-200/70">
                        <blockquote class="text-stone-700">“{{ $testimonial->content }}”</blockquote>
                        <figcaption class="mt-3 text-sm font-semibold">{{ $testimonial->name }}@if ($testimonial->location)<span class="font-normal text-stone-500"> · {{ $testimonial->location }}</span>@endif</figcaption>
                    </figure>
                @endforeach
            </div>
        @endif
    </div>

    <section id="kontak" class="mx-auto max-w-7xl scroll-mt-24 px-4 pt-24 sm:px-6" aria-labelledby="judul-kontak">
        <h2 id="judul-kontak" data-reveal class="font-display text-4xl font-bold text-husk-950">{{ __('Hubungi Kami') }}</h2>
        <p data-reveal class="mb-8 mt-3 max-w-2xl text-lg text-stone-600">{{ __('Cara tercepat memesan adalah melalui WhatsApp. Kami siap membantu memilihkan buah terbaik untuk Anda.') }}</p>
        <div class="grid gap-8 lg:grid-cols-2">
            <div class="space-y-4" data-reveal-stagger="120">
                <div data-reveal class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-whatsapp to-whatsapp-dark p-8 text-white">
                    <img src="{{ asset('images/logo.svg') }}" alt="" class="absolute -right-4 -top-4 w-24 rotate-12 animate-float opacity-90">
                    <h3 class="font-display text-2xl font-bold">WhatsApp</h3>
                    <p class="mt-2 text-white/85">{{ $site->get('phone_display') }} · {{ $site->get('opening_hours') }}</p>
                    <a href="{{ route('whatsapp.order') }}" rel="nofollow" class="mt-6 inline-flex items-center gap-2 rounded-full bg-white px-7 py-3.5 font-semibold text-whatsapp-dark shadow-lg transition hover:-translate-y-0.5">{{ __('Chat Sekarang') }} &rarr;</a>
                </div>
                <address data-reveal class="rounded-[2rem] bg-white p-8 not-italic ring-1 ring-stone-200/70">
                    <h3 class="font-display text-2xl font-bold text-husk-900">{{ __('Alamat') }}</h3>
                    <p class="mt-2 text-stone-700">{{ $site->get('site_name') }}<br>{{ $site->get('street_address') }}<br>{{ $site->get('city') }}, {{ $site->get('region') }} {{ $site->get('postal_code') }}</p>
                    @if ($site->get('email'))
                        <p class="mt-4">Email: <a href="mailto:{{ $site->get('email') }}" class="font-semibold text-husk-700 hover:underline">{{ $site->get('email') }}</a></p>
                    @endif
                    @if ($site->get('google_maps_url'))
                        <a href="{{ $site->get('google_maps_url') }}" target="_blank" rel="noopener" class="mt-4 inline-block font-semibold text-husk-700 hover:underline">{{ __('Buka di Google Maps') }} &rarr;</a>
                    @endif
                </address>
                <div data-reveal class="rounded-[2rem] bg-flesh-100 p-8">
                    <h3 class="font-display text-2xl font-bold text-husk-900">{{ __('Area Pengiriman') }}</h3>
                    <p class="mt-2 text-stone-700">{{ $site->get('service_area') }}.</p>
                </div>
            </div>

            @if ($site->get('latitude') && $site->get('longitude'))
                <iframe title="{{ __('Peta lokasi :site', ['site' => $site->get('site_name')]) }}"
                        src="https://maps.google.com/maps?q={{ $site->get('latitude') }},{{ $site->get('longitude') }}&z=13&output=embed"
                        data-reveal="right" class="min-h-[28rem] w-full rounded-[2rem] ring-1 ring-stone-200/70" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            @endif
        </div>
    </section>

    <x-site.cta-band />
</x-layouts.site>
