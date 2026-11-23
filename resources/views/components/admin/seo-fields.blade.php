@props(['model'])

<x-admin.card title="SEO (opsional)">
    <div class="space-y-4">
        <x-admin.input name="meta_title" label="Meta Title" :value="$model->meta_title" maxlength="70"
                       help="Maks. 70 karakter. Kosongkan untuk dibuat otomatis. Masukkan kata kunci utama, mis. &quot;Durian Montong Bali&quot;." />
        <x-admin.textarea name="meta_description" label="Meta Description" :value="$model->meta_description" rows="2" maxlength="170"
                          help="Maks. 170 karakter. Ringkasan menarik yang tampil di hasil pencarian Google." />
    </div>
</x-admin.card>
