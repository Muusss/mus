<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alternatif;

class AlternatifSeeder extends Seeder
{
    public function run(): void
    {
        $siswa = [
            // Kelas 6A (10 siswa)
            ['nis' => '2024001', 'nama_siswa' => 'Ahmad Fauzi', 'jk' => 'Lk', 'kelas' => '6A'],
            ['nis' => '2024002', 'nama_siswa' => 'Siti Nurhaliza', 'jk' => 'Pr', 'kelas' => '6A'],
            ['nis' => '2024003', 'nama_siswa' => 'Muhammad Rizki', 'jk' => 'Lk', 'kelas' => '6A'],
            ['nis' => '2024004', 'nama_siswa' => 'Aisyah Putri', 'jk' => 'Pr', 'kelas' => '6A'],
            ['nis' => '2024005', 'nama_siswa' => 'Dimas Pratama', 'jk' => 'Lk', 'kelas' => '6A'],
            ['nis' => '2024006', 'nama_siswa' => 'Fatimah Zahra', 'jk' => 'Pr', 'kelas' => '6A'],
            ['nis' => '2024007', 'nama_siswa' => 'Irfan Hakim', 'jk' => 'Lk', 'kelas' => '6A'],
            ['nis' => '2024008', 'nama_siswa' => 'Nadia Safira', 'jk' => 'Pr', 'kelas' => '6A'],
            ['nis' => '2024009', 'nama_siswa' => 'Farhan Maulana', 'jk' => 'Lk', 'kelas' => '6A'],
            ['nis' => '2024010', 'nama_siswa' => 'Zahra Amelia', 'jk' => 'Pr', 'kelas' => '6A'],

            // Kelas 6B (10 siswa)
            ['nis' => '2024011', 'nama_siswa' => 'Budi Santoso', 'jk' => 'Lk', 'kelas' => '6B'],
            ['nis' => '2024012', 'nama_siswa' => 'Citra Dewi', 'jk' => 'Pr', 'kelas' => '6B'],
            ['nis' => '2024013', 'nama_siswa' => 'Hafiz Rahman', 'jk' => 'Lk', 'kelas' => '6B'],
            ['nis' => '2024014', 'nama_siswa' => 'Indah Permata', 'jk' => 'Pr', 'kelas' => '6B'],
            ['nis' => '2024015', 'nama_siswa' => 'Joko Widodo', 'jk' => 'Lk', 'kelas' => '6B'],
            ['nis' => '2024016', 'nama_siswa' => 'Khadijah Husna', 'jk' => 'Pr', 'kelas' => '6B'],
            ['nis' => '2024017', 'nama_siswa' => 'Lukman Hakim', 'jk' => 'Lk', 'kelas' => '6B'],
            ['nis' => '2024018', 'nama_siswa' => 'Maryam Jamilah', 'jk' => 'Pr', 'kelas' => '6B'],
            ['nis' => '2024019', 'nama_siswa' => 'Naufal Akbar', 'jk' => 'Lk', 'kelas' => '6B'],
            ['nis' => '2024020', 'nama_siswa' => 'Olivia Rahma', 'jk' => 'Pr', 'kelas' => '6B'],

            // Kelas 6C (10 siswa)
            ['nis' => '2024021', 'nama_siswa' => 'Agus Setiawan', 'jk' => 'Lk', 'kelas' => '6C'],
            ['nis' => '2024022', 'nama_siswa' => 'Bella Safitri', 'jk' => 'Pr', 'kelas' => '6C'],
            ['nis' => '2024023', 'nama_siswa' => 'Cahya Putra', 'jk' => 'Lk', 'kelas' => '6C'],
            ['nis' => '2024024', 'nama_siswa' => 'Dina Mariana', 'jk' => 'Pr', 'kelas' => '6C'],
            ['nis' => '2024025', 'nama_siswa' => 'Eko Prasetyo', 'jk' => 'Lk', 'kelas' => '6C'],
            ['nis' => '2024026', 'nama_siswa' => 'Fitri Handayani', 'jk' => 'Pr', 'kelas' => '6C'],
            ['nis' => '2024027', 'nama_siswa' => 'Gilang Ramadhan', 'jk' => 'Lk', 'kelas' => '6C'],
            ['nis' => '2024028', 'nama_siswa' => 'Hana Pertiwi', 'jk' => 'Pr', 'kelas' => '6C'],
            ['nis' => '2024029', 'nama_siswa' => 'Ibrahim Al-Ghazi', 'jk' => 'Lk', 'kelas' => '6C'],
            ['nis' => '2024030', 'nama_siswa' => 'Jasmine Azzahra', 'jk' => 'Pr', 'kelas' => '6C'],

            // Kelas 6D (10 siswa)
            ['nis' => '2024031', 'nama_siswa' => 'Khalid Basalamah', 'jk' => 'Lk', 'kelas' => '6D'],
            ['nis' => '2024032', 'nama_siswa' => 'Laila Majnun', 'jk' => 'Pr', 'kelas' => '6D'],
            ['nis' => '2024033', 'nama_siswa' => 'Muhammad Arif', 'jk' => 'Lk', 'kelas' => '6D'],
            ['nis' => '2024034', 'nama_siswa' => 'Nur Azizah', 'jk' => 'Pr', 'kelas' => '6D'],
            ['nis' => '2024035', 'nama_siswa' => 'Omar Syarif', 'jk' => 'Lk', 'kelas' => '6D'],
            ['nis' => '2024036', 'nama_siswa' => 'Putri Ramadhani', 'jk' => 'Pr', 'kelas' => '6D'],
            ['nis' => '2024037', 'nama_siswa' => 'Qasim Abdullah', 'jk' => 'Lk', 'kelas' => '6D'],
            ['nis' => '2024038', 'nama_siswa' => 'Rania Salsabila', 'jk' => 'Pr', 'kelas' => '6D'],
            ['nis' => '2024039', 'nama_siswa' => 'Syahrul Gunawan', 'jk' => 'Lk', 'kelas' => '6D'],
            ['nis' => '2024040', 'nama_siswa' => 'Tiara Andini', 'jk' => 'Pr', 'kelas' => '6D'],
        ];

        foreach ($siswa as $s) {
            Alternatif::updateOrCreate(['nis' => $s['nis']], $s);
        }
    }
}