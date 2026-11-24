@props(['model', 'fields', 'locale' => App\Enums\Locale::English])

{{-- $fields: list of [attribute, label, rows]; rows above 1 render a textarea. --}}
<x-admin.card :title="'Terjemahan '.$locale->label().' (/'.$locale->value.')'">
    <p class="mb-5 text-xs text-stone-500">Opsional. Halaman versi ini baru masuk Google setelah {{ implode(', ', array_map(fn (string $key): string => Str::lower(collect($fields)->firstWhere(0, $key)[1]), $model::requiredTranslations())) }} diisi.</p>
    <div class="space-y-4">
        @foreach ($fields as [$attribute, $label, $rows])
            @php($key = "translations.{$locale->value}.{$attribute}")
            @php($id = "translation-{$locale->value}-{$attribute}")
            @php($value = old($key, $model->translation($attribute, $locale)))
            @php($class = 'mt-1.5 block w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm shadow-sm focus:outline-2 focus:outline-husk-500 '.($errors->has($key) ? 'border-red-400' : 'border-stone-300'))
            <div>
                <label for="{{ $id }}" class="block text-sm font-semibold text-stone-700">{{ $label }}</label>
                @if ($rows > 1)
                    <textarea id="{{ $id }}" name="translations[{{ $locale->value }}][{{ $attribute }}]" lang="{{ $locale->value }}" rows="{{ $rows }}" maxlength="{{ $model::translatableAttributes()[$attribute] }}" class="{{ $class }}">{{ $value }}</textarea>
                @else
                    <input id="{{ $id }}" name="translations[{{ $locale->value }}][{{ $attribute }}]" lang="{{ $locale->value }}" maxlength="{{ $model::translatableAttributes()[$attribute] }}" value="{{ $value }}" class="{{ $class }}">
                @endif
                @error($key)<p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
            </div>
        @endforeach
    </div>
</x-admin.card>
