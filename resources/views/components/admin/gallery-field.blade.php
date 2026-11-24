@props(['product'])

@php($galleryErrors = collect([...$errors->get('images'), ...array_merge(...array_values($errors->get('images.*')) ?: [[]])])->unique())

<div class="mt-6 border-t border-stone-200 pt-6">
    <label for="images" class="block text-sm font-semibold text-stone-700">Foto Galeri (bisa pilih banyak)</label>
    <input id="images" name="images[]" type="file" multiple accept="image/jpeg,image/png,image/webp" data-image-resize
           @if ($galleryErrors->isNotEmpty()) aria-invalid="true" aria-describedby="images-error" @endif
           @class([
               'mt-1.5 block w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm shadow-sm focus:outline-2 focus:outline-husk-500',
               'border-red-400' => $galleryErrors->isNotEmpty(),
               'border-stone-300' => $galleryErrors->isEmpty(),
           ])>
    <p class="mt-1 text-xs text-stone-500">Tampil sebagai slider di halaman produk. Maks. {{ App\Models\Product::MAX_PHOTOS }} foto per produk, termasuk foto utama. Jika foto utama kosong, foto galeri pertama yang dipakai.</p>
    <p data-image-resize-status class="mt-1 text-xs font-medium text-husk-700" aria-live="polite"></p>
    @if ($galleryErrors->isNotEmpty())
        <div id="images-error">
            @foreach ($galleryErrors as $message)
                <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
            @endforeach
        </div>
    @endif

    @if ($product->exists && $product->images->isNotEmpty())
        <ul class="mt-4 grid grid-cols-2 gap-3">
            @foreach ($product->images as $image)
                <li>
                    <label class="block cursor-pointer rounded-xl border border-stone-200 p-2 text-xs font-medium text-stone-700 has-[:checked]:border-red-400 has-[:checked]:bg-red-50">
                        <img src="{{ $image->url() }}" alt="Foto galeri {{ $loop->iteration }}" class="aspect-square w-full rounded-lg object-cover">
                        <span class="mt-2 flex items-center gap-2">
                            <input type="checkbox" name="remove_images[]" value="{{ $image->id }}" @checked(in_array((string) $image->id, array_map(strval(...), (array) old('remove_images', [])), true)) class="size-4 rounded border-stone-300 accent-red-600">
                            Hapus
                        </span>
                    </label>
                </li>
            @endforeach
        </ul>
    @endif
</div>
