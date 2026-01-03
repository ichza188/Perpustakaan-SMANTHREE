<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Siswa;
use App\Imports\SiswaImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Exports\SiswaTemplateExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Siswa::select(['id', 'nisn', 'nama', 'kelas', 'angkatan']);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama', function ($row) {
                    if ($row->status_bebas_perpus == 'disetujui') {
                        return $row->nama . ' <span class="ml-2 px-2 py-1 text-xs font-bold text-green-700 bg-green-100 rounded-full border border-green-300"> <i class="fas fa-check-circle"></i> Bebas Perpus</span>';
                    }
                    return $row->nama;
                })
                ->addColumn('action', function ($row) {
                    return '
                        <button type="button" class="edit text-yellow-600 hover:text-yellow-800 text-lg" data-id="'.$row->id.'" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="delete text-red-600 hover:text-red-800 text-lg ml-3" data-id="'.$row->id.'" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                })
                ->rawColumns(['action', 'nama'])
                ->make(true);
        }

        return view('admin.siswa.index');
    }

    public function create()
    {
        return view('admin.siswa.create');
    }

    public function store(Request $request)
    {
        // --- SKENARIO 1: JIKA USER UPLOAD EXCEL ---
        if ($request->hasFile('excel_file')) {
            $request->validate([
                'excel_file' => 'required|mimes:xlsx,xls',
            ]);

            try {
                Excel::import(new SiswaImport, $request->file('excel_file'));
                return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diimport!');
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal import: ' . $e->getMessage());
            }
        }

        // --- SKENARIO 2: JIKA USER INPUT MANUAL ---
        // Validasi input form manual
        $request->validate([
            'nama' => 'required|string|max:255',
            'nisn' => 'required|string|unique:siswa,nisn|unique:users,username', // NISN gak boleh kembar
            'kelas' => 'required|string',
            'angkatan' => 'required|numeric',
            'tanggal_lahir' => 'required|date',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
        
            // --- LOGIKA PASSWORD ---
            // Ambil input tanggal (2007-05-15) -> Ubah jadi (20070515)
            $passwordGenerated = str_replace('-', '', $request->tanggal_lahir);
    
            // 2. Buat Akun Login (User)
            $user = User::create([
                'username' => $request->nisn,
                'role'     => 'siswa',
                'password' => \Illuminate\Support\Facades\Hash::make($passwordGenerated), // Gunakan password generate
            ]);
    
            // 3. Buat Data Profil (Siswa)
            Siswa::create([
                'user_id'       => $user->id,
                'nama'          => $request->nama,
                'nisn'          => $request->nisn,
                'kelas'         => $request->kelas,
                'angkatan'      => $request->angkatan,
                'tanggal_lahir' => $request->tanggal_lahir, // Simpan format asli di data diri
            ]);
    
        });
    
        return redirect()->route('admin.siswa.index')
            ->with('success', 'Siswa berhasil ditambahkan! Password default: Tanggal Lahir (YYYYMMDD)');
    }

    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        return response()->json($siswa);
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:20',
        ]);

        $siswa->update([
            'nama' => $request->nama,
            'kelas' => $request->kelas,
        ]);

        // Update username jika NISN berubah
        if ($request->nisn != $siswa->nisn) {
            $request->validate(['nisn' => 'required|string|size:10|unique:siswa,nisn']);
            $siswa->user->update(['username' => $request->nisn]);
            $siswa->update(['nisn' => $request->nisn]);
        }

        return response()->json(['success' => 'Data berhasil diupdate!']);
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->user->delete();
        $siswa->delete();

        return response()->json(['success' => 'Data berhasil dihapus!']);
    }
    public function downloadTemplate()
    {
        return Excel::download(new SiswaTemplateExport, 'template_siswa.xlsx');
    }
}
