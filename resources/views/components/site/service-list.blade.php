@props(['services'])

@if ($services->count() > 1)
    <nav class="flex flex-wrap justify-center gap-3" aria-label="{{ __('Daftar layanan') }}">
        @foreach ($services as $service)
            <a href="#{{ $service->anchor() }}" class="rounded-full bg-white px-5 py-2.5 text-sm font-semibold text-husk-900 ring-1 ring-stone-200 transition hover:-translate-y-0.5 hover:bg-husk-950 hover:text-white hover:ring-husk-950">{{ $service->name }}</a>
        @endforeach
    </nav>
@endif

<div class="mt-12 space-y-12">
    @foreach ($services as $service)
        <article id="{{ $service->anchor() }}" data-reveal class="group grid scroll-mt-28 overflow-hidden rounded-[2rem] bg-white shadow-sm ring-1 ring-stone-200/70 lg:grid-cols-2" @unless ($service->isAvailableIn(App\Enums\Locale::current())) lang="{{ App\Enums\Locale::default()->value }}" @endunless>
            {{-- The name of the service is the highlight, on top of its photo. --}}
            <div @class(['relative isolate flex min-h-[22rem] items-end overflow-hidden sm:min-h-[28rem]', 'lg:order-last' => $loop->even])>
                <img src="{{ $service->displayImageUrl() }}" alt="" loading="lazy" class="absolute inset-0 -z-20 size-full object-cover transition duration-700 group-hover:scale-105">
                <div class="absolute inset-0 -z-10 bg-gradient-to-t from-husk-950 via-husk-950/60 to-husk-950/10"></div>
                <div class="p-7 text-white sm:p-10">
                    <h2 class="font-display text-4xl font-bold leading-[1.05] tracking-tight text-balance sm:text-5xl xl:text-6xl">{{ $service->name }}</h2>
                    <span class="mt-5 block h-1.5 w-24 rounded-full bg-flesh-400" aria-hidden="true"></span>
                </div>
            </div>
            <div class="p-7 sm:p-10">
                <p class="text-lg font-medium leading-relaxed text-husk-900 sm:text-xl">{{ $service->summary }}</p>
                <div class="prose-kebun mt-4">{{ $service->descriptionHtml() }}</div>
                <x-site.whatsapp-link :service="$service" class="mt-6">{{ __('Tanya :topic', ['topic' => $service->name]) }}</x-site.whatsapp-link>
            </div>
        </article>
    @endforeach
</div>
