@props(['title', 'description' => null])

<div class="mb-8 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h1 class="font-display text-3xl font-bold text-husk-900">{{ $title }}</h1>
        @if ($description)
            <p class="mt-1 text-sm text-stone-500">{{ $description }}</p>
        @endif
    </div>
    @isset($actions)
        <div class="flex gap-2">{{ $actions }}</div>
    @endisset
</div>
