<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'owner@gmail.com'],
            [
                'name' => 'Joshua',
                'password' => bcrypt('owner123'),
                'role' => 'owner',
            ]
        );
        
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Hasby',
                'password' => bcrypt('admin123'), 
                'role' => 'Admin',
            ]
        );
        
        User::firstOrCreate(
            ['email' => 'kasir@gmail.com'],
            [
                'name' => 'Naufal',
                'password' => bcrypt('kasir123'),
                'role' => 'Kasir',
            ]
        );
    }
}
