<x-layouts.admin title="Layanan Kami">
    <x-admin.page-header title="Layanan Kami" description="Layanan yang tampil di menu Layanan Kami (Services) di website.">
        <x-slot:actions><x-admin.button-link href="{{ route('admin.services.create') }}">+ Tambah Layanan</x-admin.button-link></x-slot:actions>
    </x-admin.page-header>

    <div class="overflow-x-auto rounded-2xl border border-stone-200 bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="bg-stone-50 text-xs uppercase tracking-wider text-stone-500">
                <tr><th class="px-4 py-3">#</th><th class="px-4 py-3">Layanan</th><th class="px-4 py-3">English</th><th class="px-4 py-3">Status</th><th class="px-4 py-3"></th></tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($services as $service)
                    <tr>
                        <td class="px-4 py-3 text-stone-500">{{ $service->sort_order }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $service->displayImageUrl() }}" alt="" class="size-12 shrink-0 rounded-lg object-cover">
                                <div><p class="font-semibold">{{ $service->name }}</p><p class="text-stone-500">{{ Str::limit($service->summary, 100) }}</p></div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-stone-600">{{ $service->isAvailableIn(App\Enums\Locale::English) ? 'Lengkap' : 'Belum lengkap' }}</td>
                        <td class="px-4 py-3"><span @class(['rounded-full px-2.5 py-0.5 text-xs font-semibold', 'bg-husk-100 text-husk-800' => $service->is_published, 'bg-stone-200 text-stone-600' => ! $service->is_published])>{{ $service->is_published ? 'Tampil' : 'Draf' }}</span></td>
                        <td class="px-4 py-3"><x-admin.row-actions :edit="route('admin.services.edit', $service)" :destroy="route('admin.services.destroy', $service)" confirm="Pindahkan layanan ini ke sampah? Masih bisa dipulihkan." /></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-stone-500">Belum ada layanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($trashedServices->isNotEmpty())
        <h2 class="mb-3 mt-10 text-sm font-semibold text-stone-700">Sampah Layanan</h2>
        <ul class="divide-y divide-stone-100 rounded-2xl border border-stone-200 bg-white text-sm shadow-sm">
            @foreach ($trashedServices as $service)
                <li class="flex items-center justify-between gap-4 px-4 py-3">
                    <span><span class="font-semibold">{{ $service->name }}</span> <span class="text-stone-500">· dihapus {{ $service->deleted_at->translatedFormat('d M Y H:i') }}</span></span>
                    <form method="POST" action="{{ route('admin.trashed-services.update', $service) }}">
                        @csrf
                        @method('PATCH')
                        <button class="rounded-lg border border-husk-300 px-3 py-1.5 text-xs font-semibold text-husk-800 hover:bg-husk-50">Pulihkan</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
</x-layouts.admin>
