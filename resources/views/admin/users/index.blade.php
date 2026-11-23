<x-layouts.admin title="Pengguna">
    <x-admin.page-header title="Pengguna" description="Kelola akun yang dapat mengakses panel admin.">
        <x-slot:actions><x-admin.button-link href="{{ route('admin.users.create') }}">+ Tambah Pengguna</x-admin.button-link></x-slot:actions>
    </x-admin.page-header>

    <div class="overflow-x-auto rounded-2xl border border-stone-200 bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="bg-stone-50 text-xs uppercase tracking-wider text-stone-500">
                <tr><th class="px-4 py-3">Nama</th><th class="px-4 py-3">Peran</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Login Terakhir</th><th class="px-4 py-3"></th></tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @foreach ($users as $user)
                    <tr>
                        <td class="px-4 py-3"><p class="font-semibold">{{ $user->name }}</p><p class="text-xs text-stone-500">{{ $user->email }}</p></td>
                        <td class="px-4 py-3">{{ $user->role->label() }}</td>
                        <td class="px-4 py-3">
                            <span @class(['rounded-full px-2.5 py-0.5 text-xs font-semibold', 'bg-husk-100 text-husk-800' => $user->is_active, 'bg-red-100 text-red-700' => ! $user->is_active])>{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        </td>
                        <td class="px-4 py-3 text-stone-600">{{ $user->last_login_at?->translatedFormat('d M Y H:i') ?? 'Belum pernah' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.activity-logs.index', ['user_id' => $user->id]) }}" class="rounded-lg border border-stone-300 px-3 py-1.5 text-xs font-semibold hover:bg-stone-100">Log</a>
                                <x-admin.row-actions :edit="route('admin.users.edit', $user)" :destroy="route('admin.users.destroy', $user)" confirm="Hapus pengguna ini?" />
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $users->links() }}</div>
</x-layouts.admin>
