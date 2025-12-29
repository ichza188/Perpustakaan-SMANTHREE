<?php

namespace App\Http\Controllers\Siswa;

use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class PeminjamanController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'admin') {
            return $this->adminIndex();
        }
        return $this->siswaIndex();
    }

    private function adminIndex()
    {
        $stats = [
            'pending'           => Peminjaman::where('status', 'pending')->count(),
            'pengajuan_kembali' => Peminjaman::where('status', 'pengajuan_kembali')->count(),
        ];

        $peminjaman = Peminjaman::with(['siswa.user', 'buku'])
            ->when(request('status'), fn($q) => $q->where('status', request('status')))
            ->latest()
            ->get();

        return view('admin.peminjaman.index', compact('peminjaman', 'stats'));
    }

    private function siswaIndex()
    {
        $peminjaman = Peminjaman::with('buku')
            ->where('siswa_id', auth()->user()->siswa->id)
            ->latest()
            ->get();

        return view('siswa.peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        $buku = Buku::whereRaw('stok > (SELECT COUNT(*) FROM peminjaman WHERE buku_id = buku.id AND status IN ("dipinjam","pengajuan_kembali"))')
            ->get();

        return view('siswa.peminjaman.create', compact('buku'));
    }

    public function store(Request $request)
    {
        $request->validate(['buku_id' => 'required|exists:buku,id']);

        $siswaId = auth()->user()->siswa->id;
        $batasMaksimal = 4;

        $jumlahPinjamanSaatIni = Peminjaman::where('siswa_id',$siswaId)
            ->whereIn('status', ['pending', 'dipinjam', 'pengajuan_kembali'])
            ->count();

        if ($jumlahPinjamanSaatIni >= $batasMaksimal) {
            return back()->with('error', "Maaf, Batas peminjaman hanya $batasMaksimal buku. Anda masih meminjam/mengajukan $jumlahPinjamanSaatIni buku.");
        }

        $buku = Buku::findOrFail($request->buku_id);
        $sedangDipinjam = $buku->peminjaman()
            ->whereIn('status', ['dipinjam', 'pengajuan_kembali'])
            ->count();

        if ($buku->stok <= $sedangDipinjam) {
            return back()->with('error', 'Stok buku habis!');
        }

        Peminjaman::create([
            'siswa_id' => auth()->user()->siswa->id,
            'buku_id' => $request->buku_id,
            'tanggal_pinjam' => now(),
            'status' => 'pending'
        ]);

        return redirect()->route('siswa.peminjaman.index')
            ->with('success', 'Pengajuan peminjaman berhasil!');
    }

    public function ajukanKembali($id)
    {
        $p = Peminjaman::where('siswa_id', auth()->user()->siswa->id)
            ->where('id', $id)
            ->where('status', 'dipinjam')
            ->firstOrFail();

        $p->update([
            'status' => 'pengajuan_kembali',
            'tanggal_pengajuan_kembali' => now()
        ]);

        return back()->with('success', 'Buku berhasil diajukan untuk dikembalikan');
    }

    public function approve($id)
    {
        $p = Peminjaman::where('status', 'pending')->findOrFail($id);

        if ($p->buku->stokTersedia() <= 0) {
            return back()->with('error', 'Stok buku habis!');
        }

        $p->update([
            'status' => 'dipinjam',
            'admin_id' => auth()->id(),
            'catatan_admin' => 'Disetujui oleh admin'
        ]);
        $p->buku->decrement('stok');
        return back()->with('success', 'Peminjaman disetujui!');
    }

    public function terimaKembali($id)
    {
        $p = Peminjaman::where('status', 'pengajuan_kembali')->findOrFail($id);

        $p->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now(),
            'admin_id' => auth()->id(),
            'catatan_admin' => 'Buku telah diterima kembali'
        ]);
        
        $p->buku->increment('stok');
        return back()->with('success', 'Buku telah dikembalikan. Stok bertambah!');
    }

    public function tolak($id, Request $request)
    {
        $p = Peminjaman::whereIn('status', ['pending', 'pengajuan_kembali'])
            ->findOrFail($id);

        if ($p->status == 'pengajuan_kembali') {
            $statusBaru = 'dipinjam';
            $pesan = 'Pengembalian ditolak. Status buku kembali menjadi DIPINJAM (Masih dibawa siswa).';
        } else {
            $statusBaru = 'ditolak';
            $pesan = 'Permintaan peminjaman berhasil ditolak.';
        }
        $p->update([
            'status' => $statusBaru,
            'admin_id' => auth()->id(),
            'catatan_admin' => $request->catatan ?? 'Ditolak oleh admin'
        ]);

        return back()->with('success', $pesan);
    }

    public function pengembalian()
    {
        $peminjaman = Peminjaman::with('buku')
            ->where('siswa_id', auth()->user()->siswa->id)
            ->whereIn('status', ['dipinjam', 'pengajuan_kembali'])
            ->latest()
            ->get();

        return view('siswa.pengembalian.index', compact('peminjaman'));
    }
}

