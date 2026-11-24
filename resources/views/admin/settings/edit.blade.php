@php
    $englishFields = array_map(fn (string $key): string => $key.'_en', App\Support\SiteSettings::TRANSLATABLE);
    $isEnglishComplete = collect($englishFields)->every(fn (string $key): bool => filled($settings[$key]));
@endphp

<x-layouts.admin title="Pengaturan">
    <x-admin.page-header title="Pengaturan Website" description="Data bisnis ini dipakai di seluruh website dan di data terstruktur Google (Local Business)." />

    <form method="POST" action="{{ route('admin.settings.update') }}" class="grid gap-6 lg:grid-cols-3">
        @csrf
        @method('PUT')

        <div class="min-w-0 lg:col-span-2">
            <x-admin.language-tabs :indonesian-fields="App\Support\SiteSettings::TRANSLATABLE" :english-fields="$englishFields" :english-complete="$isEnglishComplete">
                <x-slot:indonesian>
                    <x-admin.settings-text-fields :settings="$settings" required />
                </x-slot:indonesian>
                <x-slot:english>
                    <x-admin.settings-text-fields :settings="$settings" suffix="_en">
                        <x-slot:hint>Opsional. Jika dikosongkan, halaman Inggris (/en) memakai teks Indonesia.</x-slot:hint>
                    </x-admin.settings-text-fields>
                </x-slot:english>
            </x-admin.language-tabs>
        </div>

        <div class="space-y-6">
            <x-admin.card title="Identitas & Kontak">
                <div class="space-y-4">
                    <x-admin.input name="site_name" label="Nama Toko" :value="$settings['site_name']" required />
                    <x-admin.input name="whatsapp_number" label="Nomor WhatsApp" :value="$settings['whatsapp_number']" required help="Format 62812xxxx atau 0812xxxx. Semua tombol pesan akan diarahkan ke nomor ini." />
                    <x-admin.input name="phone_display" label="Nomor yang Ditampilkan" :value="$settings['phone_display']" required help="Contoh: 0812-3456-7890" />
                    <x-admin.input name="email" label="Email" type="email" :value="$settings['email']" />
                </div>
            </x-admin.card>

            <x-admin.card title="Alamat & Lokasi (penting untuk SEO lokal)">
                <div class="space-y-4">
                    <x-admin.input name="street_address" label="Alamat Jalan" :value="$settings['street_address']" required />
                    <x-admin.input name="city" label="Kota/Kabupaten" :value="$settings['city']" required />
                    <x-admin.input name="region" label="Provinsi" :value="$settings['region']" required />
                    <x-admin.input name="postal_code" label="Kode Pos" :value="$settings['postal_code']" />
                    <x-admin.input name="google_maps_url" label="Link Google Maps" type="url" :value="$settings['google_maps_url']" help="Link profil Google Business Anda." />
                    <div class="grid grid-cols-2 gap-4">
                        <x-admin.input name="latitude" label="Latitude" :value="$settings['latitude']" />
                        <x-admin.input name="longitude" label="Longitude" :value="$settings['longitude']" />
                    </div>
                </div>
            </x-admin.card>

            <x-admin.card title="Media Sosial">
                <div class="space-y-4">
                    @foreach (App\Support\SiteSettings::SOCIAL_MEDIA as $key => $label)
                        <x-admin.input :name="$key" :label="$label" :brand="$label" type="url" :value="$settings[$key]" placeholder="https://" />
                    @endforeach
                </div>
            </x-admin.card>

            <x-admin.card title="Marketplace">
                <div class="space-y-4">
                    <p class="text-xs text-stone-500">Isi alamat lengkap toko Anda. Yang kosong tidak ditampilkan di website.</p>
                    @foreach (App\Support\SiteSettings::MARKETPLACES as $key => $label)
                        <x-admin.input :name="$key" :label="$label" :brand="$label" type="url" :value="$settings[$key]" placeholder="https://" />
                    @endforeach
                </div>
            </x-admin.card>

            <x-admin.card title="Google">
                <div class="space-y-4">
                    <x-admin.input name="google_site_verification" label="Kode Verifikasi Google Search Console" :value="$settings['google_site_verification']" help="Isi hanya kode content=&quot;...&quot; dari tag meta verifikasi." />
                    <x-admin.input name="google_analytics_id" label="Google Analytics ID" :value="$settings['google_analytics_id']" help="Contoh: G-XXXXXXXXXX" />
                </div>
            </x-admin.card>
        </div>

        <div class="sticky bottom-4 z-10 flex justify-end lg:col-span-3"><button class="cursor-pointer rounded-xl bg-husk-700 px-6 py-2.5 text-sm font-semibold text-white shadow-lg transition hover:bg-husk-800">Simpan Pengaturan</button></div>
    </form>
</x-layouts.admin>
