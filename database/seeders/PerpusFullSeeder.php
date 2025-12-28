<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PerpusFullSeeder extends Seeder
{
    public function run()
    {
        // === 1. ADMIN ===
        $admin = User::updateOrCreate([
            'username' => 'admin',
        ], [
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        // === 2. BUKU (50 buku) ===
        $bukuList = [
            ['Fisika Dasar', 'Prof. Budi'], ['Kimia Organik', 'Dr. Siti'], ['Biologi Sel', 'Dr. Ahmad'],
            ['Sejarah Indonesia', 'Dra. Rina'], ['Ekonomi Mikro', 'Dr. Joko'], ['Sosiologi', 'Prof. Lina'],
            ['Matematika Analisis', 'M.Sc. Dika'], ['Bahasa Indonesia', 'Dra. Dewi'], ['Laskar Pelangi', 'Andrea Hirata'],
            ['Bumi Manusia', 'Pramoedya'], ['Kamus Besar Bahasa Indonesia', 'Kemdikbud'], ['Ensiklopedia Sains', 'National Geographic']
        ];

        foreach ($bukuList as $i => $b) {
            Buku::create([
                'kode_buku' => 'B' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'judul' => $b[0] . ' - Edisi ' . rand(1, 5),
                'pengarang' => $b[1],
                'stok' => rand(5, 20),
            ]);
        }

        // === 3. SISWA KELAS XII (20 siswa) ===
        $siswaXII = [];
        for ($i = 1; $i <= 20; $i++) {
            $user = User::create([
                'username' => '312' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'password' => Hash::make('siswa123'),
                'role' => 'siswa'
            ]);

            $nama = $this->namaAcak();
            $siswa = Siswa::create([
                'user_id' => $user->id,
                'nama' => $nama,
                'nisn' => '007' . rand(1000000, 9999999),
                'kelas' => 'XII ' . ['IPA', 'IPS'][rand(0,1)] . ' ' . rand(1,6),
                'angkatan' => 2022,
                'tanggal_lahir' => now()->subYears(18)->subDays(rand(0,365)),
                'status_bebas_perpus' => 'belum_ajukan'
            ]);

            $siswaXII[] = $siswa;
        }

        // === 4. PEMINJAMAN & PENGEMBALIAN (Realistis) ===
        $bukuIds = Buku::pluck('id')->toArray();

        foreach ($siswaXII as $s) {
            $jumlahPinjam = rand(2, 6);
            for ($j = 0; $j < $jumlahPinjam; $j++) {
                $bukuId = $bukuIds[array_rand($bukuIds)];

                $tanggalPinjam = now()->subDays(rand(10, 180));
                $status = ['dikembalikan', 'dipinjam', 'pengajuan_kembali'][rand(0,2)];

                $p = Peminjaman::create([
                    'siswa_id' => $s->id,
                    'buku_id' => $bukuId,
                    'tanggal_pinjam' => $tanggalPinjam,
                    'status' => $status,
                    'admin_id' => $admin->id,
                ]);

                if ($status === 'dikembalikan') {
                    $p->update([
                        'tanggal_kembali' => $tanggalPinjam->copy()->addDays(rand(3, 14)),
                    ]);
                } elseif ($status === 'pengajuan_kembali') {
                    $p->update([
                        'tanggal_pengajuan_kembali' => now()->subDays(rand(1, 5)),
                    ]);
                }
            }

            // === 5. Bebas Perpus (70% sudah bebas) ===
            if (rand(0, 100) < 70 && $s->peminjaman()->whereNotIn('status', ['dikembalikan'])->count() === 0) {
                $s->update([
                    'status_bebas_perpus' => 'disetujui',
                    'tanggal_pengajuan_bebas' => now()->subDays(rand(5, 30)),
                    'tanggal_persetujuan_bebas' => now()->subDays(rand(1, 10)),
                    'admin_bebas_perpus_id' => $admin->id,
                ]);
            }
        }

        $this->command->info('Seeder Perpus Full berhasil! 20 siswa + 50 buku + peminjaman realistis + bebas perpus!');
    }

    private function namaAcak()
    {
        $depan = ['Ahmad', 'Siti', 'Budi', 'Rina', 'Dika', 'Lina', 'Joko', 'Dewi', 'Fajar', 'Rudi'];
        $belakang = ['Santoso', 'Aisyah', 'Pratama', 'Wulandari', 'Nugroho', 'Sari', 'Hermawan', 'Lestari'];
        return $depan[array_rand($depan)] . ' ' . $belakang[array_rand($belakang)];
    }
}
