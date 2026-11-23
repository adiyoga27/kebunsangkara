<x-layouts.site
    title="Harga Durian Bali Terbaru {{ now()->year }} – Durian Lokal, Montong & Kupas"
    description="Daftar harga durian asli Bali terbaru {{ now()->year }}: durian lokal Bali, durian Montong, durian kupas & paket oleh-oleh. Matang pohon, garansi ganti, antar se-Bali."
    :canonical="route('products.index')"
    :schemas="[[
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => route('home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Harga Durian', 'item' => route('products.index')],
        ],
    ]]"
>
    <x-site.page-hero title="Harga Durian Bali Terbaru {{ now()->year }}" eyebrow="Daftar harga"
        :breadcrumbs="[['label' => 'Beranda', 'url' => route('home')], ['label' => 'Harga Durian', 'url' => null]]">
        Semua durian kami adalah <strong class="text-white">durian asli Bali</strong> yang dipanen matang dari kebun petani lokal. Harga mengikuti musim dan ukuran buah — konfirmasi stok terbaru via WhatsApp.
    </x-site.page-hero>

    <div class="mx-auto max-w-7xl px-4 pt-16 sm:px-6">
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3" data-reveal-stagger="100">
            @forelse ($products as $product)
                <div data-reveal><x-site.product-card :product="$product" heading-level="h2" class="h-full" /></div>
            @empty
                <p class="text-stone-500">Belum ada produk. Hubungi kami via WhatsApp untuk info stok.</p>
            @endforelse
        </div>

        @if ($products->isNotEmpty())
            <div data-reveal class="mt-20 overflow-x-auto rounded-[2rem] bg-white ring-1 ring-stone-200/70">
                <table class="w-full text-left text-sm">
                    <caption class="px-6 pt-8 text-left font-display text-3xl font-bold text-husk-950">Tabel Harga Durian Bali</caption>
                    <thead>
                        <tr class="border-b border-stone-200 text-stone-500">
                            <th scope="col" class="px-6 py-4 font-semibold">Jenis Durian</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Asal</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Harga</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Stok</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @foreach ($products as $product)
                            <tr class="transition hover:bg-flesh-50">
                                <td class="px-6 py-4 font-medium"><a href="{{ route('products.show', $product) }}" class="text-husk-800 hover:underline">{{ $product->name }}</a></td>
                                <td class="px-6 py-4 text-stone-600">{{ $product->origin ?? 'Bali' }}</td>
                                <td class="px-6 py-4 font-semibold">{{ $product->formattedPrice() }} <span class="font-normal text-stone-500">{{ $product->price_unit }}</span></td>
                                <td class="px-6 py-4"><span @class(['rounded-full px-3 py-1 text-xs font-bold', 'bg-husk-100 text-husk-800' => $product->is_available, 'bg-stone-100 text-stone-500' => ! $product->is_available])>{{ $product->is_available ? 'Tersedia' : 'Habis' }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if ($faqs->isNotEmpty())
            <section class="mx-auto mt-24 max-w-3xl" aria-labelledby="faq">
                <h2 id="faq" data-reveal class="text-center font-display text-4xl font-bold text-husk-950">Pertanyaan Seputar Pemesanan</h2>
                <div class="mt-8"><x-site.faq-list :faqs="$faqs" /></div>
            </section>
        @endif
    </div>

    <x-site.cta-band />
</x-layouts.site>
