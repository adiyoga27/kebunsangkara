<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (! User::query()->where('email', 'admin@durianbali.test')->exists()) {
            $password = Str::password(14, symbols: false);

            User::create([
                'name' => 'Administrator',
                'email' => 'admin@durianbali.test',
                'password' => $password,
                'role' => UserRole::Admin,
                'is_active' => true,
            ]);

            $this->command?->warn("Akun admin dibuat → email: admin@durianbali.test | password: {$password}");
            $this->command?->warn('Segera ganti email & password lewat menu Profil Saya.');
        }

        $this->call(ContentSeeder::class);
    }
}
