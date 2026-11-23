<x-layouts.site
    title="Tentang Kami – Penjual Durian Asli Bali"
    description="Kenali {{ $site->get('site_name') }}, penjual durian asli Bali yang bekerja sama langsung dengan petani lokal di {{ $site->get('city') }}. Durian matang pohon, garansi ganti."
    :canonical="route('about')"
>
    <x-site.page-hero :title="'Tentang '.$site->get('site_name')" eyebrow="Cerita kami"
        :breadcrumbs="[['label' => 'Beranda', 'url' => route('home')], ['label' => 'Tentang Kami', 'url' => null]]">
        {{ $site->get('tagline') }}.
    </x-site.page-hero>

    <div class="mx-auto max-w-3xl px-4 pt-16 sm:px-6">
        <div data-reveal class="prose-durian text-lg">
            {{-- Markdown with raw HTML stripped and unsafe links disabled, so the output is safe to print. --}}
            {!! Str::markdown($site->get('about_content'), ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
        </div>

        <div class="mt-12 grid gap-4 sm:grid-cols-3" data-reveal-stagger="120">
            @foreach (['Petani lokal Bali' => 'Kerja sama langsung', 'Dipilih manual' => 'Satu per satu', 'Garansi' => 'Ganti jika busuk'] as $title => $text)
                <div data-reveal class="rounded-[2rem] bg-husk-950 p-7 text-center text-white transition duration-500 hover:-translate-y-1.5">
                    <p class="font-display text-xl font-bold text-flesh-300">{{ $title }}</p>
                    <p class="mt-1 text-sm text-husk-200">{{ $text }}</p>
                </div>
            @endforeach
        </div>

        @if ($testimonials->isNotEmpty())
            <h2 data-reveal class="mt-20 font-display text-4xl font-bold text-husk-950">Apa Kata Pelanggan</h2>
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

    <x-site.cta-band />
</x-layouts.site>
