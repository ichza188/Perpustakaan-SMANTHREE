<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Siswa; // Pastikan ini ada
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 1. ADMIN 1
        User::create([
            'username' => 'admin',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        // 2. ADMIN 2
        User::create([
            'username' => 'admin2',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        // 3. SISWA 1 (Budi)
        $nisn1 = '0071234567';
        $tgl1  = '2007-05-15';
        
        $user1 = User::create([
            'username' => $nisn1,
            'password' => Hash::make(str_replace('-', '', $tgl1)), // 20070515
            'role'     => 'siswa',
        ]);

        $user1->siswa()->create([
            'nama'          => 'Budi Santoso',
            'tanggal_lahir' => $tgl1,
            'angkatan'      => 2024,
            'nisn'          => $nisn1,
            'kelas'         => 'X IPA 1',
        ]);

        // 3. SISWA 2 (Siti)
        $nisn2 = '0077654321';
        $tgl2  = '2007-08-22';

        $user2 = User::create([
            'username' => $nisn2,
            'password' => Hash::make(str_replace('-', '', $tgl2)), // 20070822
            'role'     => 'siswa',
        ]);

        $user2->siswa()->create([
            'nama'          => 'Siti Aisyah',
            'tanggal_lahir' => $tgl2,
            'angkatan'      => 2024,
            'nisn'          => $nisn2,
            'kelas'         => 'X IPS 2',
        ]);
    }
}