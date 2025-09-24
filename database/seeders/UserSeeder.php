<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@sikka.opendata'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password123'), // Wajib bcrypt
            ]
        );

        $admin->assignRole('admin');
    }
}
