<x-layouts.admin title="Klik WhatsApp">
    <x-admin.page-header title="Klik WhatsApp" description="Setiap pengunjung yang menekan tombol pesan tercatat di sini (calon pembeli)." />

    <div class="overflow-x-auto rounded-2xl border border-stone-200 bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="bg-stone-50 text-xs uppercase tracking-wider text-stone-500">
                <tr><th class="px-4 py-3">Waktu</th><th class="px-4 py-3">Produk</th><th class="px-4 py-3">Dari Halaman</th><th class="px-4 py-3">IP / Perangkat</th></tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($clicks as $click)
                    <tr>
                        <td class="whitespace-nowrap px-4 py-3 text-stone-600">{{ $click->created_at?->translatedFormat('d M Y H:i') }}</td>
                        <td class="px-4 py-3 font-medium">{{ $click->product?->name ?? 'Umum' }}</td>
                        <td class="max-w-xs truncate px-4 py-3 text-stone-600">{{ $click->source_url ?: '-' }}</td>
                        <td class="px-4 py-3 text-xs text-stone-500"><p class="font-mono">{{ $click->ip_address }}</p><p class="max-w-56 truncate">{{ $click->user_agent }}</p></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-stone-500">Belum ada klik.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $clicks->links() }}</div>
</x-layouts.admin>
