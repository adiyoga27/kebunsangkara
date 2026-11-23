@props(['title' => null])

<section {{ $attributes->merge(['class' => 'rounded-2xl border border-stone-200 bg-white p-6 shadow-sm']) }}>
    @if ($title)
        <h2 class="mb-5 text-base font-semibold text-stone-900">{{ $title }}</h2>
    @endif
    {{ $slot }}
</section>
