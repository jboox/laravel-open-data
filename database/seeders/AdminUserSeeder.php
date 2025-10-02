<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     */
    public function run(): void
    {
        // Pastikan role admin ada
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Buat user admin default (jika belum ada)
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'), // ⚠️ ganti setelah login pertama
            ]
        );

        // Assign role admin ke user ini
        if (! $user->hasRole('admin')) {
            $user->assignRole($adminRole);
        }

        $this->command->info('✅ Admin user siap! email: admin@example.com | password: password');
    }
}
