<x-layouts.admin title="FAQ">
    <x-admin.page-header title="FAQ" description="Pertanyaan umum tampil di beranda & halaman harga, dan membantu SEO.">
        <x-slot:actions><x-admin.button-link href="{{ route('admin.faqs.create') }}">+ Tambah FAQ</x-admin.button-link></x-slot:actions>
    </x-admin.page-header>

    <div class="overflow-x-auto rounded-2xl border border-stone-200 bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="bg-stone-50 text-xs uppercase tracking-wider text-stone-500">
                <tr><th class="px-4 py-3">#</th><th class="px-4 py-3">Pertanyaan</th><th class="px-4 py-3">Tampil</th><th class="px-4 py-3"></th></tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($faqs as $faq)
                    <tr>
                        <td class="px-4 py-3 text-stone-500">{{ $faq->sort_order }}</td>
                        <td class="px-4 py-3"><p class="font-semibold">{{ $faq->question }}</p><p class="text-stone-500">{{ Str::limit($faq->answer, 100) }}</p></td>
                        <td class="px-4 py-3">{{ $faq->is_published ? 'Ya' : 'Tidak' }}</td>
                        <td class="px-4 py-3"><x-admin.row-actions :edit="route('admin.faqs.edit', $faq)" :destroy="route('admin.faqs.destroy', $faq)" /></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-stone-500">Belum ada FAQ.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $faqs->links() }}</div>
</x-layouts.admin>
