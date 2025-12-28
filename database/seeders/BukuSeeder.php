<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Buku;
use Illuminate\Support\Str;

class BukuSeeder extends Seeder
{
    public function run()
    {
        // Daftar judul & pengarang realistis
        $judulList = [
            'Fisika Dasar', 'Kimia Organik', 'Biologi Sel', 'Matematika Analisis',
            'Sejarah Indonesia', 'Ekonomi Mikro', 'Sosiologi Masyarakat', 'Geografi Fisik',
            'Bahasa Indonesia', 'Bahasa Inggris', 'Seni Budaya', 'Pendidikan Agama',
            'Algoritma Pemrograman', 'Basis Data', 'Jaringan Komputer', 'Sistem Operasi',
            'Kewirausahaan', 'Akuntansi Dasar', 'Manajemen Bisnis', 'Pemasaran Digital'
        ];

        $pengarangList = [
            'Prof. Dr. Budi Santoso', 'Dr. Siti Aisyah', 'Ir. Ahmad Fauzi',
            'Dra. Rina Wulandari', 'M.Sc. Dika Pratama', 'Ph.D. Lina Sari',
            'M.Pd. Joko Widodo', 'S.Kom. Rudi Hermawan', 'M.Eng. Fajar Nugroho',
            'S.Pd. Dewi Lestari', 'Dr. Eng. Agus Setiawan', 'M.T. Rina Puspita'
        ];

        $kategori = ['IPA', 'IPS', 'BAH', 'TEK', 'BIS'];

        $counter = 1;

        foreach ($kategori as $kat) {
            for ($i = 1; $i <= 20; $i++) {
                $kode = $kat . str_pad($counter, 3, '0', STR_PAD_LEFT);
                $judul = $judulList[array_rand($judulList)];
                $pengarang = $pengarangList[array_rand($pengarangList)];
                $stok = rand(3, 15);

                Buku::create([
                    'kode_buku' => $kode,
                    'judul' => $judul . ' - Edisi ' . rand(1, 5),
                    'pengarang' => $pengarang,
                    'stok' => $stok,
                ]);

                $counter++;
            }
        }

        // Tambah 10 buku spesial
        $bukuSpesial = [
            ['SP001', 'Kamus Besar Bahasa Indonesia', 'Tim Penyusun KBBI', 25],
            ['SP002', 'Ensiklopedia Sains', 'National Geographic', 12],
            ['SP003', 'Novel Laskar Pelangi', 'Andrea Hirata', 30],
            ['SP004', 'Bumi Manusia', 'Pramoedya Ananta Toer', 20],
            ['SP005', 'Panduan OSN Fisika', 'Tim Olimpiade', 8],
        ];

        foreach ($bukuSpesial as $b) {
            Buku::create([
                'kode_buku' => $b[0],
                'judul' => $b[1],
                'pengarang' => $b[2],
                'stok' => $b[3],
            ]);
        }
    }
}
