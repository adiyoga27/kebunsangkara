<x-layouts.site
    :title="$offering->label()"
    :description="$offering->intro()"
    :canonical="route('offerings.show', $offering)"
    :schemas="[[
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => __('Beranda'), 'item' => route('home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => $offering->label(), 'item' => route('offerings.show', $offering)],
        ],
    ]]"
>
    <x-site.page-hero :title="$offering->label()" :eyebrow="$site->get('site_name')" image="images/kebun/kebun-alpukat.webp"
        :breadcrumbs="[['label' => __('Beranda'), 'url' => route('home')], ['label' => $offering->label(), 'url' => null]]">
        {{ $offering->intro() }}
    </x-site.page-hero>

    <div class="mx-auto max-w-7xl px-4 pt-12 sm:px-6">
        @if ($products->isNotEmpty())
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3" data-reveal-stagger="100">
                @foreach ($products as $product)
                    <div data-reveal><x-site.product-card :product="$product" heading-level="h2" class="h-full" /></div>
                @endforeach
            </div>
            <div class="mt-12 text-center">
                <x-site.whatsapp-link :offering="$offering">{{ __('Tanya :topic', ['topic' => $offering->label()]) }}</x-site.whatsapp-link>
            </div>
        @else
            <div data-reveal class="flex flex-col items-center rounded-[2rem] bg-white px-6 py-16 text-center ring-1 ring-stone-200/70">
                <span class="text-5xl" aria-hidden="true">{{ $offering->emoji() }}</span>
                <h2 class="mt-4 font-display text-2xl font-bold text-husk-950">{{ __('Informasi :topic sedang disiapkan', ['topic' => $offering->label()]) }}</h2>
                <p class="mt-2 max-w-md text-stone-600">{{ __('Hubungi kami untuk menanyakan pilihan, harga, dan ketersediaannya.') }}</p>
                <x-site.whatsapp-link :offering="$offering" class="mt-6">{{ __('Tanya :topic', ['topic' => $offering->label()]) }}</x-site.whatsapp-link>
            </div>
        @endif
    </div>
</x-layouts.site>
