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
            ['email' => 'admin@assunnah.sch.id'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
                'kelas'    => null,
            ]
        );

        // Wali Kelas 6A
        User::updateOrCreate(
            ['email' => 'wali6a@assunnah.sch.id'],
            [
                'name'     => 'Ustadz Ahmad Faisal',
                'password' => Hash::make('wali123'),
                'role'     => 'wali_kelas',
                'kelas'    => '6A',
            ]
        );

        // Wali Kelas 6B
        User::updateOrCreate(
            ['email' => 'wali6b@assunnah.sch.id'],
            [
                'name'     => 'Ustadzah Siti Aisyah',
                'password' => Hash::make('wali123'),
                'role'     => 'wali_kelas',
                'kelas'    => '6B',
            ]
        );

        // Wali Kelas 6C
        User::updateOrCreate(
            ['email' => 'wali6c@assunnah.sch.id'],
            [
                'name'     => 'Ustadz Muhammad Rizki',
                'password' => Hash::make('wali123'),
                'role'     => 'wali_kelas',
                'kelas'    => '6C',
            ]
        );

        // Wali Kelas 6D
        User::updateOrCreate(
            ['email' => 'wali6d@assunnah.sch.id'],
            [
                'name'     => 'Ustadzah Nur Hidayah',
                'password' => Hash::make('wali123'),
                'role'     => 'wali_kelas',
                'kelas'    => '6D',
            ]
        );
    }
}