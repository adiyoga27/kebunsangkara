@props(['settings', 'suffix' => '', 'required' => false])

{{-- Settings that exist once per language. $suffix: "" for Indonesian, "_en" for English. --}}
<x-admin.card title="Teks Website">
    <div class="space-y-4">
        @if (isset($hint))
            <p class="text-xs text-stone-500">{{ $hint }}</p>
        @endif
        <x-admin.input :name="'tagline'.$suffix" label="Tagline" :value="$settings['tagline'.$suffix]" :required="$required" maxlength="150" />
        <x-admin.input :name="'opening_hours'.$suffix" label="Jam Buka" :value="$settings['opening_hours'.$suffix]" :required="$required" maxlength="150" />
        <x-admin.textarea :name="'service_area'.$suffix" label="Area Pengiriman" :value="$settings['service_area'.$suffix]" rows="2" :required="$required" maxlength="300" />
        <x-admin.textarea :name="'whatsapp_message'.$suffix" label="Pesan Otomatis WhatsApp" :value="$settings['whatsapp_message'.$suffix]" rows="2" :required="$required" maxlength="300" />
        <x-admin.textarea :name="'about_content'.$suffix" label="Isi Halaman Tentang Kami" :value="$settings['about_content'.$suffix]" rows="8" :required="$required" data-rich-text />
    </div>
</x-admin.card>

<x-admin.card title="SEO Beranda">
    <div class="space-y-4">
        <x-admin.input :name="'home_meta_title'.$suffix" label="Meta Title Beranda" :value="$settings['home_meta_title'.$suffix]" :required="$required" maxlength="70" />
        <x-admin.textarea :name="'home_meta_description'.$suffix" label="Meta Description Beranda" :value="$settings['home_meta_description'.$suffix]" rows="2" :required="$required" maxlength="170" />
    </div>
</x-admin.card>
