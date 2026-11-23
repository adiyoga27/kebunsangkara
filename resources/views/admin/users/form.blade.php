<x-layouts.admin :title="$user->exists ? 'Ubah Pengguna' : 'Tambah Pengguna'">
    <x-admin.page-header :title="$user->exists ? 'Ubah Pengguna' : 'Tambah Pengguna'" />

    <form method="POST" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}" class="max-w-2xl space-y-6">
        @csrf
        @if ($user->exists) @method('PUT') @endif
        <x-admin.card>
            <div class="space-y-4">
                <x-admin.input name="name" label="Nama" :value="$user->name" required />
                <x-admin.input name="email" label="Email" type="email" :value="$user->email" required />
                <div>
                    <label for="role" class="block text-sm font-semibold text-stone-700">Peran</label>
                    <select id="role" name="role" class="mt-1.5 block w-full rounded-xl border border-stone-300 bg-white px-3.5 py-2.5 text-sm">
                        @foreach (App\Enums\UserRole::cases() as $role)
                            <option value="{{ $role->value }}" @selected(old('role', $user->role?->value) === $role->value)>{{ $role->label() }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-stone-500">Administrator: akses penuh. Editor: hanya kelola produk, artikel, testimoni & FAQ.</p>
                    @error('role')<p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                </div>
                <x-admin.checkbox name="is_active" label="Akun aktif (boleh login)" :checked="$user->is_active" />
            </div>
        </x-admin.card>
        <x-admin.card :title="$user->exists ? 'Ganti Password (opsional)' : 'Password'">
            <div class="grid gap-4 sm:grid-cols-2">
                <x-admin.input name="password" label="Password" type="password" autocomplete="new-password" :required="! $user->exists" />
                <x-admin.input name="password_confirmation" label="Ulangi Password" type="password" autocomplete="new-password" />
            </div>
        </x-admin.card>
        <x-admin.form-actions :cancel="route('admin.users.index')" />
    </form>
</x-layouts.admin>
