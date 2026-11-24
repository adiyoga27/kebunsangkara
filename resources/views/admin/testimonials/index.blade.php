<x-layouts.admin title="Testimoni">
    <x-admin.page-header title="Testimoni" description="Ulasan pembeli di bagian bawah beranda. Sembilan ulasan terbaru yang berstatus Tampil akan muncul.">
        <x-slot:actions><x-admin.button-link href="{{ route('admin.testimonials.create') }}">+ Tambah Testimoni</x-admin.button-link></x-slot:actions>
    </x-admin.page-header>

    <form class="mb-4 flex flex-wrap gap-2">
        <input name="q" value="{{ request('q') }}" placeholder="Cari nama atau ulasan..." aria-label="Cari testimoni" class="w-full max-w-xs rounded-xl border border-stone-300 bg-white px-3.5 py-2 text-sm">
        <select name="status" onchange="this.form.submit()" class="rounded-xl border border-stone-300 bg-white px-3.5 py-2 text-sm" aria-label="Status">
            <option value="">Semua status</option>
            <option value="tampil" @selected(request('status') === 'tampil')>Tampil</option>
            <option value="draf" @selected(request('status') === 'draf')>Draf</option>
        </select>
    </form>

    <div class="overflow-x-auto rounded-2xl border border-stone-200 bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="bg-stone-50 text-xs uppercase tracking-wider text-stone-500">
                <tr><th class="px-4 py-3">Pembeli</th><th class="px-4 py-3">Produk</th><th class="px-4 py-3">Ulasan</th><th class="px-4 py-3">Rating</th><th class="px-4 py-3">Status</th><th class="px-4 py-3"></th></tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($testimonials as $testimonial)
                    <tr>
                        <td class="px-4 py-3 font-semibold"><div class="flex items-center gap-3"><x-site.testimonial-avatar :testimonial="$testimonial" /><div>{{ $testimonial->name }}<p class="text-xs font-normal text-stone-500">{{ $testimonial->location }}</p></div></div></td>
                        <td class="px-4 py-3 text-stone-600">{{ $testimonial->product?->name ?? '—' }}</td>
                        <td class="max-w-md px-4 py-3 text-stone-600">{{ Str::limit($testimonial->content, 100) }}</td>
                        <td class="whitespace-nowrap px-4 py-3 text-flesh-500">{{ str_repeat('★', $testimonial->rating) }}</td>
                        <td class="px-4 py-3"><span @class(['rounded-full px-2.5 py-0.5 text-xs font-semibold', 'bg-husk-100 text-husk-800' => $testimonial->is_published, 'bg-stone-200 text-stone-600' => ! $testimonial->is_published])>{{ $testimonial->is_published ? 'Tampil' : 'Draf' }}</span></td>
                        <td class="px-4 py-3"><div class="flex justify-end gap-2">
                            <form method="POST" action="{{ route('admin.testimonials.toggle-publication', $testimonial) }}">
                                @csrf
                                @method('PATCH')
                                <button class="rounded-lg border border-husk-300 px-3 py-1.5 text-xs font-semibold text-husk-800 hover:bg-husk-50">{{ $testimonial->is_published ? 'Sembunyikan' : 'Tampilkan' }}</button>
                            </form>
                            <x-admin.row-actions :edit="route('admin.testimonials.edit', $testimonial)" :destroy="route('admin.testimonials.destroy', $testimonial)" />
                        </div></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-stone-500">{{ request()->hasAny(['q', 'status']) ? 'Tidak ada testimoni yang cocok.' : 'Belum ada testimoni.' }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $testimonials->links() }}</div>
</x-layouts.admin>
