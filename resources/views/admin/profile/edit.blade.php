<x-layouts.admin title="Profil Saya">
    <x-admin.page-header title="Profil Saya" />

    <form method="POST" action="{{ route('admin.profile.update') }}" class="max-w-2xl space-y-6">
        @csrf
        @method('PUT')
        <x-admin.card>
            <div class="space-y-4">
                <x-admin.input name="name" label="Nama" :value="$user->name" required />
                <x-admin.input name="email" label="Email" type="email" :value="$user->email" required />
            </div>
        </x-admin.card>
        <x-admin.card title="Ganti Password">
            <div class="space-y-4">
                <x-admin.input name="current_password" label="Password Saat Ini" type="password" autocomplete="current-password" />
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-admin.input name="password" label="Password Baru" type="password" autocomplete="new-password" />
                    <x-admin.input name="password_confirmation" label="Ulangi Password Baru" type="password" autocomplete="new-password" />
                </div>
            </div>
        </x-admin.card>
        <x-admin.form-actions :cancel="route('admin.dashboard')" />
    </form>
</x-layouts.admin>
