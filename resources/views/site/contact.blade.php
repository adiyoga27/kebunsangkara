<x-layouts.site
    title="Kontak & Lokasi – Pesan Buah via WhatsApp"
    description="Hubungi {{ $site->get('site_name') }} untuk pesan durian, alpukat, jeruk & manggis segar. WhatsApp {{ $site->get('phone_display') }}. Lokasi {{ $site->get('city') }}, Bali. Antar ke seluruh Bali."
    :canonical="route('contact')"
>
    <x-site.page-hero title="Hubungi Kami" eyebrow="Kontak"
        :breadcrumbs="[['label' => 'Beranda', 'url' => route('home')], ['label' => 'Kontak', 'url' => null]]">
        Cara tercepat memesan adalah melalui WhatsApp. Kami siap membantu memilihkan buah terbaik untuk Anda.
    </x-site.page-hero>

    <div class="mx-auto max-w-7xl px-4 pt-16 sm:px-6">
        <div class="grid gap-8 lg:grid-cols-2">
            <div class="space-y-4" data-reveal-stagger="120">
                <div data-reveal class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-whatsapp to-whatsapp-dark p-8 text-white">
                    <img src="{{ asset('images/logo.svg') }}" alt="" class="absolute -right-4 -top-4 w-24 rotate-12 animate-float opacity-90">
                    <h2 class="font-display text-2xl font-bold">WhatsApp</h2>
                    <p class="mt-2 text-white/85">{{ $site->get('phone_display') }} · {{ $site->get('opening_hours') }}</p>
                    <a href="{{ route('whatsapp.order') }}" rel="nofollow" class="mt-6 inline-flex items-center gap-2 rounded-full bg-white px-7 py-3.5 font-semibold text-whatsapp-dark shadow-lg transition hover:-translate-y-0.5">Chat Sekarang &rarr;</a>
                </div>
                <address data-reveal class="rounded-[2rem] bg-white p-8 not-italic ring-1 ring-stone-200/70">
                    <h2 class="font-display text-2xl font-bold text-husk-900">Alamat</h2>
                    <p class="mt-2 text-stone-700">{{ $site->get('site_name') }}<br>{{ $site->get('street_address') }}<br>{{ $site->get('city') }}, {{ $site->get('region') }} {{ $site->get('postal_code') }}</p>
                    @if ($site->get('email'))
                        <p class="mt-4">Email: <a href="mailto:{{ $site->get('email') }}" class="font-semibold text-husk-700 hover:underline">{{ $site->get('email') }}</a></p>
                    @endif
                    @if ($site->get('google_maps_url'))
                        <a href="{{ $site->get('google_maps_url') }}" target="_blank" rel="noopener" class="mt-4 inline-block font-semibold text-husk-700 hover:underline">Buka di Google Maps &rarr;</a>
                    @endif
                </address>
                <div data-reveal class="rounded-[2rem] bg-flesh-100 p-8">
                    <h2 class="font-display text-2xl font-bold text-husk-900">Area Pengiriman</h2>
                    <p class="mt-2 text-stone-700">{{ $site->get('service_area') }}.</p>
                </div>
            </div>

            @if ($site->get('latitude') && $site->get('longitude'))
                <iframe title="Peta lokasi {{ $site->get('site_name') }}"
                        src="https://maps.google.com/maps?q={{ $site->get('latitude') }},{{ $site->get('longitude') }}&z=13&output=embed"
                        data-reveal="right" class="min-h-[28rem] w-full rounded-[2rem] ring-1 ring-stone-200/70" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            @endif
        </div>
    </div>
</x-layouts.site>
