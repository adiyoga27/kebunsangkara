@props(['product', 'headingLevel' => 'h3'])

<article data-tilt="7" {{ $attributes->class(['group relative flex flex-col rounded-[2rem] bg-white p-3 shadow-sm ring-1 ring-stone-200/70 hover:shadow-2xl hover:shadow-husk-900/15']) }}>
    <a href="{{ route('products.show', $product) }}" class="relative block aspect-[4/3.4] overflow-hidden rounded-[1.6rem] bg-gradient-to-br from-husk-50 via-flesh-50 to-flesh-100" tabindex="-1" aria-hidden="true">
        <span class="absolute left-1/2 top-1/2 size-3/4 -translate-x-1/2 -translate-y-1/2 rounded-full bg-flesh-300/50 blur-2xl transition duration-700 group-hover:scale-125 group-hover:bg-flesh-300/70"></span>
        @if ($product->imageUrl())
            <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }} asli Bali" width="480" height="408" loading="lazy" decoding="async"
                 class="relative size-full object-cover transition duration-700 ease-out group-hover:scale-110">
        @else
            <img src="{{ asset('images/durian.svg') }}" alt="{{ $product->name }} asli Bali" width="480" height="408" loading="lazy" decoding="async"
                 class="relative size-full object-contain p-6 drop-shadow-xl transition duration-700 ease-out group-hover:-translate-y-2 group-hover:rotate-6 group-hover:scale-110">
        @endif
        <span class="tilt-glare pointer-events-none absolute inset-0"></span>

        <span class="absolute left-3 top-3 flex flex-wrap gap-1.5">
            @if ($product->is_featured)
                <span class="rounded-full bg-husk-950/85 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-flesh-300 backdrop-blur">★ Terlaris</span>
            @endif
            @unless ($product->is_available)
                <span class="rounded-full bg-white/90 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-stone-700 backdrop-blur">Stok Habis</span>
            @endunless
        </span>
        @if ($product->origin)
            <span class="absolute bottom-3 left-3 rounded-full bg-white/75 px-3 py-1 text-xs font-semibold text-husk-800 backdrop-blur-md">📍 {{ $product->origin }}</span>
        @endif
    </a>

    <div class="flex flex-1 flex-col px-3 pb-3 pt-5">
        <{{ $headingLevel }} class="font-display text-xl font-bold leading-snug text-stone-900">
            <a href="{{ route('products.show', $product) }}" class="after:absolute after:inset-0 after:rounded-[2rem]">{{ $product->name }}</a>
        </{{ $headingLevel }}>
        <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-stone-500">{{ $product->short_description }}</p>

        <div class="mt-auto flex items-end justify-between gap-3 pt-5">
            <p>
                <span class="block text-xs font-medium text-stone-400">Harga {{ $product->price_unit }}</span>
                <span class="font-display text-2xl font-bold text-husk-800">{{ $product->formattedPrice() }}</span>
            </p>
            @if ($product->is_available)
                <x-site.whatsapp-link :product="$product" size="sm" class="relative z-10">Pesan</x-site.whatsapp-link>
            @else
                <span class="relative z-10 inline-flex size-10 items-center justify-center rounded-full bg-stone-100 text-stone-500 transition group-hover:bg-husk-700 group-hover:text-white" aria-hidden="true">&rarr;</span>
            @endif
        </div>
    </div>
</article>
