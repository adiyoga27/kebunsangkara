<x-layouts.admin title="Pengaturan">
    <x-admin.page-header title="Pengaturan Website" description="Data bisnis ini dipakai di seluruh website dan di data terstruktur Google (Local Business)." />

    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        <x-admin.card title="Identitas & WhatsApp">
            <div class="grid gap-4 sm:grid-cols-2">
                <x-admin.input name="site_name" label="Nama Toko" :value="$settings['site_name']" required />
                <x-admin.input name="tagline" label="Tagline" :value="$settings['tagline']" required />
                <x-admin.input name="whatsapp_number" label="Nomor WhatsApp" :value="$settings['whatsapp_number']" required help="Format 62812xxxx atau 0812xxxx. Semua tombol pesan akan diarahkan ke nomor ini." />
                <x-admin.input name="phone_display" label="Nomor yang Ditampilkan" :value="$settings['phone_display']" required help="Contoh: 0812-3456-7890" />
                <div class="sm:col-span-2">
                    <x-admin.textarea name="whatsapp_message" label="Pesan Otomatis WhatsApp" :value="$settings['whatsapp_message']" rows="2" required />
                </div>
                <x-admin.input name="email" label="Email" type="email" :value="$settings['email']" />
                <x-admin.input name="opening_hours" label="Jam Buka" :value="$settings['opening_hours']" required />
            </div>
        </x-admin.card>

        <x-admin.card title="Alamat & Lokasi (penting untuk SEO lokal)">
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2"><x-admin.input name="street_address" label="Alamat Jalan" :value="$settings['street_address']" required /></div>
                <x-admin.input name="city" label="Kota/Kabupaten" :value="$settings['city']" required />
                <x-admin.input name="region" label="Provinsi" :value="$settings['region']" required />
                <x-admin.input name="postal_code" label="Kode Pos" :value="$settings['postal_code']" />
                <x-admin.input name="google_maps_url" label="Link Google Maps" type="url" :value="$settings['google_maps_url']" help="Link profil Google Business Anda." />
                <x-admin.input name="latitude" label="Latitude" :value="$settings['latitude']" />
                <x-admin.input name="longitude" label="Longitude" :value="$settings['longitude']" />
                <div class="sm:col-span-2"><x-admin.textarea name="service_area" label="Area Pengiriman" :value="$settings['service_area']" rows="2" required /></div>
            </div>
        </x-admin.card>

        <x-admin.card title="Media Sosial">
            <div class="grid gap-4 sm:grid-cols-3">
                <x-admin.input name="instagram_url" label="Instagram" type="url" :value="$settings['instagram_url']" />
                <x-admin.input name="facebook_url" label="Facebook" type="url" :value="$settings['facebook_url']" />
                <x-admin.input name="tiktok_url" label="TikTok" type="url" :value="$settings['tiktok_url']" />
            </div>
        </x-admin.card>

        <x-admin.card title="SEO Beranda & Konten">
            <div class="space-y-4">
                <x-admin.input name="home_meta_title" label="Meta Title Beranda" :value="$settings['home_meta_title']" maxlength="70" required />
                <x-admin.textarea name="home_meta_description" label="Meta Description Beranda" :value="$settings['home_meta_description']" rows="2" maxlength="170" required />
                <x-admin.textarea name="about_content" label="Isi Halaman Tentang Kami (Markdown)" :value="$settings['about_content']" rows="8" required />
            </div>
        </x-admin.card>

        <x-admin.card title="Google">
            <div class="grid gap-4 sm:grid-cols-2">
                <x-admin.input name="google_site_verification" label="Kode Verifikasi Google Search Console" :value="$settings['google_site_verification']" help="Isi hanya kode content=&quot;...&quot; dari tag meta verifikasi." />
                <x-admin.input name="google_analytics_id" label="Google Analytics ID" :value="$settings['google_analytics_id']" help="Contoh: G-XXXXXXXXXX" />
            </div>
        </x-admin.card>

        <div class="flex justify-end"><button class="rounded-xl bg-husk-700 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-husk-800">Simpan Pengaturan</button></div>
    </form>
</x-layouts.admin>
