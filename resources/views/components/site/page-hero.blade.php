@props(['title', 'eyebrow' => null, 'breadcrumbs' => []])

<section data-spotlight class="grain relative isolate mx-auto mt-2 max-w-7xl overflow-hidden rounded-[2.5rem] bg-husk-950 px-6 py-16 text-white sm:mx-6 sm:px-12 sm:py-20">
    <div class="spotlight pointer-events-none absolute inset-0 -z-10"></div>
    <div class="pointer-events-none absolute -right-24 -top-24 -z-10 size-96 animate-blob bg-flesh-500/20 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-32 left-10 -z-10 size-80 animate-blob bg-husk-600/50 blur-3xl [animation-delay:-7s]"></div>
    <img src="{{ asset('images/durian-pod.svg') }}" alt="" class="pointer-events-none absolute right-10 top-10 hidden w-20 rotate-12 animate-float lg:block" data-depth="40">
    <img src="{{ asset('images/leaf.svg') }}" alt="" class="pointer-events-none absolute bottom-10 right-40 hidden w-24 -rotate-12 animate-bob lg:block" data-depth="-30">
    <img src="{{ asset('images/durian.svg') }}" alt="" class="pointer-events-none absolute -bottom-20 -right-10 w-56 opacity-20 sm:w-72 lg:opacity-100 lg:right-56 lg:w-60 lg:animate-float-slow" data-depth="20">

    <div class="relative max-w-3xl">
        @if ($breadcrumbs)
            <div data-reveal class="[&_a]:text-husk-200 [&_a:hover]:text-white [&_li]:text-husk-200/60 [&_span[aria-current]]:text-flesh-300">
                <x-site.breadcrumbs :items="$breadcrumbs" />
            </div>
        @endif
        @if ($eyebrow)
            <p data-reveal class="mt-6 text-sm font-bold uppercase tracking-[0.2em] text-flesh-300">{{ $eyebrow }}</p>
        @endif
        <h1 data-reveal style="--reveal-delay: 100ms" class="mt-3 font-display text-4xl font-bold leading-[1.05] tracking-tight sm:text-6xl">{{ $title }}</h1>
        @if ($slot->isNotEmpty())
            <div data-reveal style="--reveal-delay: 200ms" class="mt-6 max-w-2xl text-lg leading-relaxed text-husk-100/80">{{ $slot }}</div>
        @endif
    </div>
</section>
