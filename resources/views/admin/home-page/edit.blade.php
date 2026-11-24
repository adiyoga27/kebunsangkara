<x-layouts.admin title="Home Page">
    <x-admin.page-header title="Home Page" description="Atur tulisan di halaman beranda website.">
        <x-slot:actions><x-admin.button-link href="{{ route('home') }}#marquee" target="_blank" rel="noopener">Lihat Beranda</x-admin.button-link></x-slot:actions>
    </x-admin.page-header>

    <form method="POST" action="{{ route('admin.home-page.update') }}" class="grid gap-6 lg:grid-cols-3">
        @csrf
        @method('PUT')

        <div class="min-w-0 lg:col-span-2">
            <x-admin.language-tabs :indonesian-fields="['home_marquee']" :english-fields="['home_marquee_en']" :english-complete="filled($settings['home_marquee_en'])">
                <x-slot:indonesian>
                    <x-admin.card title="Tulisan Bergerak">
                        <x-admin.textarea name="home_marquee" label="Tulisan" :value="$settings['home_marquee']" rows="10" required
                                          help="Satu baris untuk satu tulisan, urut dari atas. Boleh diawali emoji, mis. 🍈 Durian Matang Pohon. Maks. 20 baris, 60 karakter per baris." />
                    </x-admin.card>
                </x-slot:indonesian>
                <x-slot:english>
                    <x-admin.card title="Tulisan Bergerak (English)">
                        <x-admin.textarea name="home_marquee_en" label="Tulisan" :value="$settings['home_marquee_en']" rows="10" lang="en"
                                          help="Opsional. Jika kosong, halaman Inggris memakai tulisan Indonesia." />
                    </x-admin.card>
                </x-slot:english>
            </x-admin.language-tabs>
        </div>

        <div class="space-y-6">
            <x-admin.card title="Tampilan">
                <x-admin.checkbox name="home_marquee_enabled" label="Tampilkan tulisan bergerak di beranda" :checked="$settings['home_marquee_enabled'] === '1'" />
            </x-admin.card>
            <x-admin.form-actions :cancel="route('admin.dashboard')" />
        </div>
    </form>
</x-layouts.admin>
