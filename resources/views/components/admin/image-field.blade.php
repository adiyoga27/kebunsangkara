@props(['currentUrl' => null, 'label' => 'Gambar', 'help' => 'Gunakan foto asli (bukan dari internet) agar lebih dipercaya Google & pembeli.'])

<div>
    <x-admin.input name="image" :label="$label" type="file" accept="image/jpeg,image/png,image/webp" :attributes="$attributes"
                   :help="'JPG/PNG/WEBP, maks. 4 MB. '.$help" />
    @if ($currentUrl)
        <div class="mt-3 flex items-center gap-4">
            <img src="{{ $currentUrl }}" alt="" class="size-20 rounded-xl object-cover">
            <x-admin.checkbox name="remove_image" label="Hapus gambar saat ini" />
        </div>
    @endif
</div>
