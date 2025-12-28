<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Hash;

class PerpustakaanFullSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Memulai seeding data perpustakaan (100% AMAN & ANTI ERROR)...');

        // === 1. ADMIN (PASTIKAN ADA) ===
        $admin = User::updateOrCreate(
            ['username' => 'admin'],
            ['password' => Hash::make('admin123'), 'role' => 'admin']
        );

        $this->command->info("Admin: {$admin->username} (ID: {$admin->id})");

        // === 2. BUKU (ANTI DUPLIKAT) ===
        $bukuData = [
            ['B0001', 'Fisika Dasar', 'Prof. Budi Santoso'],
            ['B0002', 'Kimia Analitik', 'Dr. Siti Nurhaliza'],
            ['B0003', 'Biologi Molekuler', 'Dr. Ahmad Fauzi'],
            ['B0004', 'Matematika Diskrit', 'M.Sc. Rina Wulandari'],
            ['B0005', 'Sejarah Indonesia', 'Dra. Dewi Lestari'],
            ['B0006', 'Ekonomi Makro', 'Dr. Joko Widodo'],
            ['B0007', 'Sosiologi Modern', 'Prof. Lina Marlina'],
            ['B0008', 'Geografi Fisik', 'Ir. Fajar Nugroho'],
            ['B0009', 'Bahasa Indonesia', 'Dra. Rini Susanti'],
            ['B0010', 'English for Teens', 'John Smith'],
        ];

        foreach ($bukuData as $b) {
            Buku::updateOrCreate(
                ['kode_buku' => $b[0]],
                ['judul' => $b[1] . ' - Edisi ' . rand(1, 5), 'pengarang' => $b[2], 'stok' => rand(5, 25)]
            );
        }

        $this->command->info('Buku berhasil dibuat (tanpa duplikat)!');

        // === 3. SISWA + PEMINJAMAN + BEBAS PERPUS ===
        $bukuIds = Buku::pluck('id')->toArray();

        for ($kelas = 10; $kelas <= 12; $kelas++) {
            $jumlahSiswa = $kelas == 12 ? 25 : 30;

            for ($i = 1; $i <= $jumlahSiswa; $i++) {
                $jurusan = ['IPA', 'IPS'][array_rand(['IPA', 'IPS'])];
                $sub = rand(1, 6);
                $username = $kelas . $jurusan . str_pad($i, 3, '0', STR_PAD_LEFT);

                if (User::where('username', $username)->exists()) {
                    continue;
                }

                $user = User::create([
                    'username' => $username,
                    'password' => Hash::make('siswa123'),
                    'role' => 'siswa'
                ]);

                $namaDepan = ['Ahmad', 'Siti', 'Budi', 'Rina', 'Dika', 'Lina', 'Joko', 'Dewi', 'Fajar', 'Rudi'];
                $namaBelakang = ['Santoso', 'Aisyah', 'Pratama', 'Wulandari', 'Nugroho', 'Sari', 'Hermawan', 'Lestari'];

                $siswa = Siswa::create([
                    'user_id' => $user->id,
                    'nama' => $namaDepan[array_rand($namaDepan)] . ' ' . $namaBelakang[array_rand($namaBelakang)],
                    'nisn' => '007' . rand(1000000, 9999999),
                    'kelas' => ($kelas == 10 ? 'X' : ($kelas == 11 ? 'XI' : 'XII')) . ' ' . $jurusan . ' ' . $sub,
                    'angkatan' => 2022 + ($kelas - 10),
                    'tanggal_lahir' => now()->subYears(15 + ($kelas - 10))->subDays(rand(0, 365)),
                ]);

                // === PEMINJAMAN ===
                $jumlahPinjam = $kelas == 12 ? rand(1, 3) : rand(2, 8);

                for ($j = 0; $j < $jumlahPinjam; $j++) {
                    $bukuId = $bukuIds[array_rand($bukuIds)];

                    if (Peminjaman::where('siswa_id', $siswa->id)->where('buku_id', $bukuId)->exists()) {
                        continue;
                    }

                    $tanggalPinjam = now()->subDays(rand(5, 200));
                    $status = match (true) {
                        $kelas == 12 && $j < $jumlahPinjam - 1 => 'dikembalikan',
                        $kelas == 12 => rand(0, 1) ? 'dipinjam' : 'dikembalikan',
                        default => ['pending', 'dipinjam', 'pengajuan_kembali'][rand(0, 2)]
                    };

                    $p = Peminjaman::create([
                        'siswa_id' => $siswa->id,
                        'buku_id' => $bukuId,
                        'tanggal_pinjam' => $tanggalPinjam,
                        'status' => $status,
                        'admin_id' => $admin->id, // ← PAKAI $admin->id (PASTI ADA!)
                    ]);

                    if ($status === 'dikembalikan') {
                        $p->update(['tanggal_kembali' => $tanggalPinjam->copy()->addDays(rand(3, 14))]);
                    } elseif ($status === 'pengajuan_kembali') {
                        $p->update(['tanggal_pengajuan_kembali' => now()->subDays(rand(1, 7))]);
                    }
                }

                // === BEBAS PERPUS ===
                if ($kelas == 12 && rand(0, 100) < 70) {
                    $belumKembali = $siswa->peminjaman()
                        ->whereNotIn('status', ['dikembalikan'])
                        ->exists();

                    if (!$belumKembali && $siswa->status_bebas_perpus !== 'disetujui') {
                        $siswa->update([
                            'status_bebas_perpus' => 'disetujui',
                            'tanggal_pengajuan_bebas' => now()->subDays(rand(10, 60)),
                            'tanggal_persetujuan_bebas' => now()->subDays(rand(1, 10)),
                            'admin_bebas_perpus_id' => $admin->id,
                        ]);
                    }
                }
            }
        }

        $this->command->info('SEEDER SELESAI! BISA DIJALANKAN BERKALI-KALI TANPA ERROR!');
    }
}
