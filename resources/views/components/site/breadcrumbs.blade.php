@props(['items'])

{{-- $items: list of ['label' => string, 'url' => ?string]; the last item is the current page. --}}
<nav aria-label="Breadcrumb" class="text-sm text-stone-500">
    <ol class="flex flex-wrap items-center gap-1.5">
        @foreach ($items as $item)
            <li class="flex items-center gap-1.5">
                @if (! $loop->first)
                    <span aria-hidden="true">/</span>
                @endif
                @if (! $loop->last && $item['url'])
                    <a href="{{ $item['url'] }}" class="hover:text-husk-700 hover:underline">{{ $item['label'] }}</a>
                @else
                    <span aria-current="page" class="text-stone-700">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
