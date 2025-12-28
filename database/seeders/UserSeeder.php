<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin
        $admin = User::create([
            'username' => 'admin',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        // Siswa 1
        $siswa1 = User::create([
            'username' => 'siswa001',
            'password' => bcrypt('siswa123'),
            'role' => 'siswa',
        ]);

        $siswa1->siswa()->create([
            'nama' => 'Budi Santoso',
            'tanggal_lahir' => '2007-05-15',
            'angkatan' => 2024,
            'nisn' => '0071234567',
            'kelas' => 'X IPA 1',
        ]);

        // Siswa 2
        $siswa2 = User::create([
            'username' => 'siswa002',
            'password' => bcrypt('siswa123'),
            'role' => 'siswa',
        ]);

        $siswa2->siswa()->create([
            'nama' => 'Siti Aisyah',
            'tanggal_lahir' => '2007-08-22',
            'angkatan' => 2024,
            'nisn' => '0077654321',
            'kelas' => 'X IPS 2',
        ]);
    }
}
