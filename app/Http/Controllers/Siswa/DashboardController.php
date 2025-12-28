<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;
        $peminjaman = $siswa->peminjaman()->with('buku')->latest()->take(5)->get();
        $totalPinjam = $siswa->peminjaman()->where('status', 'dipinjam')->count();
        $totalKembali = $siswa->peminjaman()->where('status', 'dikembalikan')->count();

        // Data untuk dropdown
        $tingkatan = ['X', 'XI', 'XII'];
        $jurusan = ['IPA', 'IPS'];
        $subKelas = ['1', '2', '3', '4', '5', '6'];

        return view('siswa.dashboard', compact(
            'siswa', 'peminjaman', 'totalPinjam', 'totalKembali',
            'tingkatan', 'jurusan', 'subKelas'
        ));
    }

    public function updateProfil(Request $request)
{
    $request->validate([
        'tingkatan' => 'required|in:X,XI,XII',
        'jurusan' => 'required|in:IPA,IPS',
        'sub_kelas' => 'required|in:1,2,3,4,5,6'
    ]);

    $siswa = auth()->user()->siswa;

    $kelasBaru = $request->tingkatan . ' ' . $request->jurusan . ' ' . $request->sub_kelas;

    $data = ['kelas' => $kelasBaru];



    $siswa->update($data);

    return back()->with('success', 'Kelas berhasil diperbarui menjadi: ' . $kelasBaru);
}
}
