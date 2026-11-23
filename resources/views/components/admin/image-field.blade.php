@props(['currentUrl' => null])

<div>
    <x-admin.input name="image" label="Gambar" type="file" accept="image/jpeg,image/png,image/webp"
                   help="JPG/PNG/WEBP, maks. 4 MB. Gunakan foto asli (bukan dari internet) agar lebih dipercaya Google & pembeli." />
    @if ($currentUrl)
        <div class="mt-3 flex items-center gap-4">
            <img src="{{ $currentUrl }}" alt="" class="size-20 rounded-xl object-cover">
            <x-admin.checkbox name="remove_image" label="Hapus gambar saat ini" />
        </div>
    @endif
</div>
