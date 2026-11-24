@props(['testimonial'])

@if ($testimonial->imageUrl())
    <img src="{{ $testimonial->imageUrl() }}" alt="" width="48" height="48" loading="lazy" class="size-12 shrink-0 rounded-full object-cover ring-2 ring-white shadow-sm">
@else
    <span class="flex size-12 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-husk-500 to-husk-800 font-bold text-white" aria-hidden="true">{{ Str::upper(Str::substr($testimonial->name, 0, 1)) }}</span>
@endif
