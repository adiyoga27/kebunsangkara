@props(['links', 'theme' => 'light', 'iconOnly' => false, 'rel' => 'noopener'])

{{-- Links to social media or marketplaces with their icon. $links: label => URL. --}}
<div {{ $attributes->class('flex flex-wrap gap-3') }}>
    @foreach ($links as $label => $url)
        <a href="{{ $url }}" target="_blank" rel="{{ $rel }}" @if ($iconOnly) aria-label="{{ $label }}" title="{{ $label }}" @endif @class([
            'inline-flex items-center justify-center gap-2 rounded-full font-semibold ring-1 transition duration-300 hover:-translate-y-0.5 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-flesh-400',
            'size-11' => $iconOnly,
            'px-5 py-2.5 text-sm' => ! $iconOnly,
            'bg-white text-stone-800 shadow-sm ring-stone-200 hover:shadow-md' => $theme === 'light',
            'bg-white/5 text-white ring-white/10 hover:bg-flesh-400 hover:text-husk-950' => $theme === 'dark',
        ])>
            <x-site.brand-icon :brand="$label" :colored="$theme === 'light'" />
            @unless ($iconOnly){{ $label }}@endunless
        </a>
    @endforeach
</div>
