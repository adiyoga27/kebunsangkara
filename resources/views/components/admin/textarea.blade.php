@props(['name', 'label', 'value' => null, 'rows' => 4, 'help' => null])

<div>
    <label for="{{ $name }}" class="block text-sm font-semibold text-stone-700">{{ $label }}</label>
    <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $rows }}"
              @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror
              {{ $attributes->class([
                  'mt-1.5 block w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm shadow-sm focus:outline-2 focus:outline-husk-500',
                  'border-red-400' => $errors->has($name),
                  'border-stone-300' => ! $errors->has($name),
              ]) }}>{{ old($name, $value) }}</textarea>
    @if ($help)
        <p class="mt-1 text-xs text-stone-500">{{ $help }}</p>
    @endif
    @error($name)
        <p id="{{ $name }}-error" class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
    @enderror
</div>
