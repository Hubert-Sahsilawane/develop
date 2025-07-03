<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Hindari duplikat: akan membuat user jika belum ada
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('admin123'), // Atau password lain
                'role' => 'admin',
            ]
        );

        // Tambahkan user lain jika perlu, contoh kasir:
        User::firstOrCreate(
            ['email' => 'kasir@gmail.com'],
            [
                'name' => 'Kasir',
                'password' => bcrypt('kasir123'),
                'role' => 'kasir',
            ]
        );
    }
}
