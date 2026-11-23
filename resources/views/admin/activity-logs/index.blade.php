<x-layouts.admin title="Log Aktivitas">
    <x-admin.page-header title="Log Aktivitas Pengguna" description="Semua login, logout, percobaan login gagal, dan perubahan data di panel admin." />

    <form class="mb-4 grid gap-3 rounded-2xl border border-stone-200 bg-white p-4 shadow-sm sm:grid-cols-6">
        <select name="user_id" class="rounded-xl border border-stone-300 px-3 py-2 text-sm" aria-label="Pengguna">
            <option value="">Semua pengguna</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" @selected((int) ($filters['user_id'] ?? 0) === $user->id)>{{ $user->name }}</option>
            @endforeach
        </select>
        <select name="action" class="rounded-xl border border-stone-300 px-3 py-2 text-sm" aria-label="Aksi">
            <option value="">Semua aksi</option>
            @foreach ($actions as $action)
                <option value="{{ $action->value }}" @selected(($filters['action'] ?? null) === $action->value)>{{ $action->label() }}</option>
            @endforeach
        </select>
        <input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="rounded-xl border border-stone-300 px-3 py-2 text-sm" aria-label="Dari tanggal">
        <input type="date" name="until" value="{{ $filters['until'] ?? '' }}" class="rounded-xl border border-stone-300 px-3 py-2 text-sm" aria-label="Sampai tanggal">
        <input name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari deskripsi / IP" class="rounded-xl border border-stone-300 px-3 py-2 text-sm">
        <div class="flex gap-2">
            <button class="flex-1 rounded-xl bg-husk-700 px-4 py-2 text-sm font-semibold text-white">Filter</button>
            <a href="{{ route('admin.activity-logs.index') }}" class="rounded-xl border border-stone-300 px-3 py-2 text-sm">Reset</a>
        </div>
    </form>

    <div class="overflow-x-auto rounded-2xl border border-stone-200 bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="bg-stone-50 text-xs uppercase tracking-wider text-stone-500">
                <tr><th class="px-4 py-3">Waktu</th><th class="px-4 py-3">Pengguna</th><th class="px-4 py-3">Aksi</th><th class="px-4 py-3">Deskripsi</th><th class="px-4 py-3">IP / Perangkat</th></tr>
            </thead>
            <tbody class="divide-y divide-stone-100 align-top">
                @forelse ($logs as $log)
                    <tr>
                        <td class="whitespace-nowrap px-4 py-3 text-stone-600">{{ $log->created_at?->translatedFormat('d M Y H:i:s') }}</td>
                        <td class="px-4 py-3">{{ $log->user?->name ?? ($log->properties['email'] ?? 'Tamu') }}</td>
                        <td class="px-4 py-3"><span class="whitespace-nowrap rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $log->action->badgeClasses() }}">{{ $log->action->label() }}</span></td>
                        <td class="px-4 py-3">
                            {{ $log->description }}
                            @if ($log->properties)
                                <details class="mt-1">
                                    <summary class="text-xs font-semibold text-husk-700">Lihat detail</summary>
                                    <pre class="mt-2 max-h-64 max-w-xl overflow-auto rounded-lg bg-stone-900 p-3 text-xs text-stone-100">{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
                                </details>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-stone-500"><p class="font-mono">{{ $log->ip_address }}</p><p class="max-w-56 truncate" title="{{ $log->user_agent }}">{{ $log->user_agent }}</p></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-stone-500">Tidak ada log yang cocok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $logs->links() }}</div>
</x-layouts.admin>
