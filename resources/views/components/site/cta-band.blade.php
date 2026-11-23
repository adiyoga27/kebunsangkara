<section class="mx-auto mt-24 max-w-7xl px-4 sm:px-6" data-reveal="zoom">
    <div data-spotlight class="grain relative overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-flesh-300 via-flesh-400 to-flesh-500 px-6 py-16 text-center sm:px-16 sm:py-20">
        <div class="spotlight pointer-events-none absolute inset-0"></div>
        <img src="{{ asset('images/kebun/buah-manggis.webp') }}" alt="" class="pointer-events-none absolute -left-8 top-8 size-24 animate-float rounded-full object-cover shadow-2xl ring-4 ring-white/60 sm:left-10 sm:size-32" data-depth="30">
        <img src="{{ asset('images/kebun/alpukat-di-pohon.webp') }}" alt="" class="pointer-events-none absolute -right-6 bottom-6 size-20 animate-float-slow rounded-full object-cover shadow-2xl ring-4 ring-white/60 [animation-delay:-3s] sm:right-12 sm:size-28" data-depth="-40">
        <img src="{{ asset('images/leaf.svg') }}" alt="" class="pointer-events-none absolute right-1/4 top-4 hidden w-20 -rotate-12 animate-bob lg:block" data-depth="20">
        <div class="relative">
            <h2 class="mx-auto max-w-3xl font-display text-4xl font-bold leading-tight text-husk-950 sm:text-5xl">Buah segar dari kebun, siap diantar ke rumah Anda.</h2>
            <p class="mx-auto mt-5 max-w-xl text-lg text-husk-900/80">Hasil panen terbatas setiap harinya. Pesan sekarang sebelum kehabisan!</p>
            <div class="mt-9"><x-site.whatsapp-link size="lg">Pesan via WhatsApp</x-site.whatsapp-link></div>
            <p class="mt-5 text-sm font-medium text-husk-900/70">{{ $site->get('phone_display') }} · {{ $site->get('opening_hours') }}</p>
        </div>
    </div>
</section>
