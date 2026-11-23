<x-layouts.admin title="Artikel">
    <x-admin.page-header title="Artikel" description="Artikel blog membantu website muncul di Google untuk banyak kata kunci.">
        <x-slot:actions><x-admin.button-link href="{{ route('admin.articles.create') }}">+ Tulis Artikel</x-admin.button-link></x-slot:actions>
    </x-admin.page-header>

    <form class="mb-4"><input name="q" value="{{ request('q') }}" placeholder="Cari artikel..." class="w-full max-w-xs rounded-xl border border-stone-300 bg-white px-3.5 py-2 text-sm"></form>

    <div class="overflow-x-auto rounded-2xl border border-stone-200 bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="bg-stone-50 text-xs uppercase tracking-wider text-stone-500">
                <tr><th class="px-4 py-3">Judul</th><th class="px-4 py-3">Penulis</th><th class="px-4 py-3">Status</th><th class="px-4 py-3"></th></tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($articles as $article)
                    <tr>
                        <td class="px-4 py-3">
                            <a href="{{ route('articles.show', $article) }}" target="_blank" class="font-semibold hover:underline">{{ $article->title }}</a>
                            <p class="text-xs text-stone-500">/artikel/{{ $article->slug }}</p>
                        </td>
                        <td class="px-4 py-3">{{ $article->author?->name ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @if ($article->is_published && $article->published_at?->isPast())
                                <span class="rounded-full bg-husk-100 px-2.5 py-0.5 text-xs font-semibold text-husk-800">Terbit {{ $article->published_at->translatedFormat('d M Y') }}</span>
                            @elseif ($article->is_published)
                                <span class="rounded-full bg-sky-100 px-2.5 py-0.5 text-xs font-semibold text-sky-800">Terjadwal {{ $article->published_at?->translatedFormat('d M Y') }}</span>
                            @else
                                <span class="rounded-full bg-stone-200 px-2.5 py-0.5 text-xs font-semibold text-stone-600">Draft</span>
                            @endif
                        </td>
                        <td class="px-4 py-3"><x-admin.row-actions :edit="route('admin.articles.edit', $article)" :destroy="route('admin.articles.destroy', $article)" /></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-stone-500">Belum ada artikel.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $articles->links() }}</div>
</x-layouts.admin>
