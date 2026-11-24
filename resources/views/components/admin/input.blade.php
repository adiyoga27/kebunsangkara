@props(['name', 'label', 'value' => null, 'type' => 'text', 'help' => null, 'brand' => null])

<div>
    <label for="{{ $name }}" @class(['text-sm font-semibold text-stone-700', 'flex items-center gap-2' => $brand, 'block' => ! $brand])>@if ($brand)<x-site.brand-icon :brand="$brand" colored />@endif{{ $label }}</label>
    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}"
           @unless (in_array($type, ['password', 'file'])) value="{{ old($name, $value) }}" @endunless
           @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror
           {{ $attributes->class([
               'mt-1.5 block w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm shadow-sm focus:outline-2 focus:outline-husk-500',
               'border-red-400' => $errors->has($name),
               'border-stone-300' => ! $errors->has($name),
           ]) }}>
    @if ($help)
        <p class="mt-1 text-xs text-stone-500">{{ $help }}</p>
    @endif
    @error($name)
        <p id="{{ $name }}-error" class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
    @enderror
</div>
