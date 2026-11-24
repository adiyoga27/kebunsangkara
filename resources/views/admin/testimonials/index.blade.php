<x-layouts.admin title="Testimoni">
    <x-admin.page-header title="Testimoni" description="Ulasan pelanggan yang tampil di website.">
        <x-slot:actions><x-admin.button-link href="{{ route('admin.testimonials.create') }}">+ Tambah Testimoni</x-admin.button-link></x-slot:actions>
    </x-admin.page-header>

    <div class="overflow-x-auto rounded-2xl border border-stone-200 bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="bg-stone-50 text-xs uppercase tracking-wider text-stone-500">
                <tr><th class="px-4 py-3">Nama</th><th class="px-4 py-3">Ulasan</th><th class="px-4 py-3">Rating</th><th class="px-4 py-3">Tampil</th><th class="px-4 py-3"></th></tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($testimonials as $testimonial)
                    <tr>
                        <td class="px-4 py-3 font-semibold">{{ $testimonial->name }}<p class="text-xs font-normal text-stone-500">{{ implode(' · ', array_filter([$testimonial->location, $testimonial->product?->name])) }}</p></td>
                        <td class="max-w-md px-4 py-3 text-stone-600">{{ Str::limit($testimonial->content, 100) }}</td>
                        <td class="px-4 py-3 text-flesh-500">{{ str_repeat('★', $testimonial->rating) }}</td>
                        <td class="px-4 py-3">{{ $testimonial->is_published ? 'Ya' : 'Tidak' }}</td>
                        <td class="px-4 py-3"><x-admin.row-actions :edit="route('admin.testimonials.edit', $testimonial)" :destroy="route('admin.testimonials.destroy', $testimonial)" /></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-stone-500">Belum ada testimoni.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $testimonials->links() }}</div>
</x-layouts.admin>
