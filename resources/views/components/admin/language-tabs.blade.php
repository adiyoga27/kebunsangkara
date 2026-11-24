@props(['indonesian', 'english', 'indonesianFields', 'englishFields', 'englishComplete' => null])

{{-- Tabs that separate the Indonesian fields from their English translation. Fields in the hidden tab are still submitted.
     $indonesianFields, $englishFields: field names whose validation errors belong to each tab ("translations.*" is allowed).
     $englishComplete: whether the translation is complete, or null to show no status. --}}
@php
    $indonesianHasErrors = $errors->hasAny($indonesianFields);
    $englishHasErrors = $errors->hasAny($englishFields);
    $isEnglishActive = $englishHasErrors && ! $indonesianHasErrors;

    $tabs = [
        ['id' => 'id', 'label' => 'Indonesia', 'active' => ! $isEnglishActive, 'hasErrors' => $indonesianHasErrors, 'content' => $indonesian, 'status' => null],
        ['id' => 'en', 'label' => 'English', 'active' => $isEnglishActive, 'hasErrors' => $englishHasErrors, 'content' => $english, 'status' => match ($englishComplete) { true => 'Lengkap', false => 'Belum lengkap', null => null }],
    ];
@endphp

<div data-tabs {{ $attributes }}>
    <div role="tablist" aria-label="Bahasa" class="mb-6 grid grid-cols-2 gap-1 rounded-2xl border border-stone-200 bg-white p-1 shadow-sm">
        @foreach ($tabs as $tab)
            <button type="button" role="tab" id="language-tab-{{ $tab['id'] }}" aria-controls="language-panel-{{ $tab['id'] }}"
                    aria-selected="{{ $tab['active'] ? 'true' : 'false' }}" tabindex="{{ $tab['active'] ? '0' : '-1' }}"
                    class="flex cursor-pointer items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold text-stone-600 transition hover:bg-stone-100 focus-visible:outline-2 focus-visible:outline-husk-500 aria-selected:bg-husk-700 aria-selected:text-white">
                <span class="rounded-md bg-stone-200 px-1.5 py-0.5 text-xs font-bold uppercase text-stone-700">{{ $tab['id'] }}</span>
                {{ $tab['label'] }}
                @if ($tab['hasErrors'])
                    <span class="rounded-full bg-red-100 px-2 py-0.5 text-[11px] font-semibold text-red-800">Perlu diperbaiki</span>
                @elseif ($tab['status'])
                    <span @class(['rounded-full px-2 py-0.5 text-[11px] font-semibold max-sm:hidden', 'bg-husk-100 text-husk-800' => $tab['status'] === 'Lengkap', 'bg-flesh-100 text-stone-700' => $tab['status'] !== 'Lengkap'])>{{ $tab['status'] }}</span>
                @endif
            </button>
        @endforeach
    </div>

    @foreach ($tabs as $tab)
        <div role="tabpanel" id="language-panel-{{ $tab['id'] }}" aria-labelledby="language-tab-{{ $tab['id'] }}" class="space-y-6" @unless ($tab['active']) hidden @endunless>
            {{ $tab['content'] }}
        </div>
    @endforeach
</div>
