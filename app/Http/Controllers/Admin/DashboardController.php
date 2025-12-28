<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
// use App\Models\Buku;
// use App\Models\Peminjaman;
use Illuminate\Support\Facades\DB;
use App\Models\Buku;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung statistik
        $totalBuku = Buku::count();
        $sedangDipinjam = Peminjaman::where('status', 'dipinjam')->count();
        $pengajuanKembali = Peminjaman::where('status', 'pengajuan_kembali')->count();
        $bebasPerpus = Siswa::where('status_bebas_perpus', 'disetujui')->count();

        $aktivitasTerbaru = Peminjaman::with(['siswa', 'buku'])
            ->latest()
            ->take(8)
            ->get();

        $bebasPerpusPending = Siswa::where('status_bebas_perpus', 'diajukan')
            ->latest()
            ->take(6)
            ->get();
            $chartPeminjaman = Peminjaman::selectRaw('MONTH(tanggal_pinjam) as bulan, COUNT(*) as total')
            ->whereYear('tanggal_pinjam', now()->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();
        $labels = []; $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $labels[] = \Carbon\Carbon::create()->month($i)->translatedFormat('F');
            $item = $chartPeminjaman->firstWhere('bulan', $i);
            $data[] = $item ? $item->total : 0;
        }
        $chartPeminjaman = ['labels' => $labels, 'data' => $data];

        // Buku Terpopuler
        $chartBukuPopuler = Buku::withCount('peminjaman')
            ->orderBy('peminjaman_count', 'desc')
            ->take(5)
            ->get();
        $chartBukuPopuler = [
            'labels' => $chartBukuPopuler->pluck('judul')->toArray(),
            'data' => $chartBukuPopuler->pluck('peminjaman_count')->toArray()
        ];

        // Status Peminjaman
        $chartStatus = [
            Peminjaman::where('status', 'pending')->count(),
            Peminjaman::where('status', 'dipinjam')->count(),
            Peminjaman::where('status', 'pengajuan_kembali')->count(),
            Peminjaman::where('status', 'dikembalikan')->count(),
        ];

        // Bebas Perpus
        $chartBebasPerpus = [
            'labels' => ['Disetujui', 'Diajukan', 'Belum'],
            'data' => [
                Siswa::where('status_bebas_perpus', 'disetujui')->count(),
                Siswa::where('status_bebas_perpus', 'diajukan')->count(),
                Siswa::where('status_bebas_perpus', 'belum_ajukan')->count(),
            ]
        ];


        return view('admin.dashboard', compact(
            'totalBuku', 'sedangDipinjam', 'pengajuanKembali', 'bebasPerpus',
            'aktivitasTerbaru', 'bebasPerpusPending','chartPeminjaman', 'chartBukuPopuler', 'chartStatus', 'chartBebasPerpus'
        ));
    }
}
