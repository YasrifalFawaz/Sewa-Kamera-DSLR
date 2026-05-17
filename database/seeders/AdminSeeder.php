<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'alamat' => 'Kantor Pusat',
            'no_telp' => '08123456789',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
    }
}