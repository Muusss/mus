<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('password'),
                'role'     => 'admin',
                'kelas'    => null,
            ]
        );

        // Contoh wali kelas 6A
        User::updateOrCreate(
            ['email' => 'wali6a@example.com'],
            [
                'name'     => 'Wali 6A',
                'password' => Hash::make('password'),
                'role'     => 'wali_kelas',
                'kelas'    => '6A',
            ]
        );

        // (opsional) wali kelas lain
        User::updateOrCreate(
            ['email' => 'wali6b@example.com'],
            [
                'name'     => 'Wali 6B',
                'password' => Hash::make('password'),
                'role'     => 'wali_kelas',
                'kelas'    => '6B',
            ]
        );
    }
}
