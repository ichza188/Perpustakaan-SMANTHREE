<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BebasPerpusController extends Controller
{
    // ================== SISWA: Halaman Bebas Perpus ==================
    public function index()
    {
        $siswa = auth()->user()->siswa;

        // Cek apakah ada buku yang belum dikembalikan (status bukan 'dikembalikan', 'ditolak']))
        $belumKembali = $siswa->peminjaman()
            ->whereNotIn('status', ['dikembalikan', 'ditolak'])
            ->exists();

        return view('siswa.bebas-perpus.index', compact('siswa', 'belumKembali'));
    }

    // ================== SISWA: Ajukan Bebas Perpus ==================
    public function ajukan()
    {
        $siswa = auth()->user()->siswa;

        // Hanya kelas XII
        if (!str_starts_with($siswa->kelas, 'XII')) {
            return back()->with('error', 'Hanya siswa kelas XII yang dapat mengajukan bebas perpustakaan!');
        }

        // Cek apakah ada buku yang belum dikembalikan
        $belumKembali = $siswa->peminjaman()
            ->whereNotIn('status', ['dikembalikan', 'ditolak'])
            ->exists();

        if ($belumKembali) {
            return back()->with('error', 'Masih ada buku yang belum dikembalikan! Silakan kembalikan semua buku terlebih dahulu.');
        }

        // Cek apakah sudah diajukan atau disetujui
        if (in_array($siswa->status_bebas_perpus, ['diajukan', 'disetujui'])) {
            return back()->with('error', 'Anda sudah mengajukan atau sudah bebas perpustakaan!');
        }

        $siswa->update([
            'status_bebas_perpus' => 'diajukan',
            'tanggal_pengajuan_bebas' => now(),
        ]);
        // Di method ajukan() — tambahkan ini
        if ($siswa->status_bebas_perpus === 'ditolak') {
            // Reset status ditolak → bisa ajukan ulang
            $siswa->update([
                'status_bebas_perpus' => 'diajukan',
                'tanggal_pengajuan_bebas' => now(),
                'catatan_bebas_perpus' => null, // optional: hapus catatan lama
            ]);

            return back()->with('success', 'Pengajuan ulang berhasil dikirim!');
        }
        return back()->with('success', 'Pengajuan Bebas Perpustakaan berhasil dikirim! Silakan tunggu persetujuan admin.');
    }

    // ================== ADMIN: Daftar Pengajuan ==================
    public function adminIndex()
    {
        $pengajuan = Siswa::with('user')
            ->whereIn('status_bebas_perpus', ['diajukan', 'disetujui'])
            ->when(request('status'), fn($q) => $q->where('status_bebas_perpus', request('status')))
            ->latest('tanggal_pengajuan_bebas')
            ->get();

        return view('admin.bebas-perpus.index', compact('pengajuan'));
    }

    // ================== ADMIN: Proses Pengajuan (Setujui / Tolak) ==================
    public function proses(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'aksi' => 'required|in:setujui,tolak',
            'catatan' => 'nullable|string|max:500'
        ]);

        if ($request->aksi === 'setujui') {
            $siswa->update([
                'status_bebas_perpus' => 'disetujui',
                'tanggal_persetujuan_bebas' => now(),
                'admin_bebas_perpus_id' => auth()->id(),
                'catatan_bebas_perpus' => $request->catatan
            ]);
            $msg = 'Bebas Perpustakaan telah disetujui!';
        } else {
            $siswa->update([
                'status_bebas_perpus' => 'ditolak',
                'catatan_bebas_perpus' => $request->catatan ?? 'Ditolak oleh admin'
            ]);
            $msg = 'Pengajuan Bebas Perpustakaan ditolak';
        }

        return back()->with('success', $msg);
    }

    // ================== CETAK SURAT BEBAS PERPUS (PDF) ==================
    public function cetak($id)
    {
        $siswa = Siswa::findOrFail($id);

        // Hanya boleh cetak jika sudah disetujui
        if ($siswa->status_bebas_perpus !== 'disetujui') {
            abort(403, 'Surat hanya dapat dicetak jika sudah disetujui');
        }

        $pdf = Pdf::loadView('admin.bebas-perpus.cetak', compact('siswa'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Surat-Bebas-Perpus-' . $siswa->nisn . '-' . $siswa->nama . '.pdf');
    }
    // app/Http/Controllers/BebasPerpusController.php

    public function verify($id)
    {
        $siswa = Siswa::findOrFail($id);

        if ($siswa->status_bebas_perpus !== 'disetujui') {
            return view('bebas-perpus.invalid');
        }

        return view('bebas-perpus.valid', compact('siswa'));
    }
}
