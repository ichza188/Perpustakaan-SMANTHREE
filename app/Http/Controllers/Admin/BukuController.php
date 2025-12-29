<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Imports\BukuImport;
use App\Exports\BukuTemplateExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Buku::select(['id', 'kode_buku', 'judul', 'pengarang', 'stok']);

            return DataTables::of($data)
                ->addIndexColumn()
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
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('buku.index');
    }

    public function create()
    {
        return view('buku.create');
    }

    public function store(Request $request)
    {
        if ($request->hasFile('excel_file')) {
            $request->validate([
                'excel_file' => 'required|mimes:xlsx,xls',
            ]);

            try {
                Excel::import(new BukuImport, $request->file('excel_file'));
                return redirect()->route('admin.buku.index')->with('success', 'Data buku berhasil diimport!');
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal import: ' . $e->getMessage());
            }
        }

        $request->validate([
            'kode_buku' => 'required|string|unique:buku,kode_buku',
            'judul'     => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'stok'      => 'required|integer|min:0',
        ]);

        Buku::create([
            'kode_buku' => $request->kode_buku,
            'judul'     => $request->judul,
            'pengarang' => $request->pengarang,
            'stok'      => $request->stok,
        ]);

        return redirect()->route('admin.buku.index')
            ->with('success', 'Buku berhasil ditambahkan manual!');
    }

    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        return response()->json($buku);
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        $request->validate([
            'kode_buku' => 'required|string|max:20|unique:buku,kode_buku,' . $id,
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:100',
            'stok' => 'required|integer|min:0',
        ]);

        $buku->update($request->only(['kode_buku', 'judul', 'pengarang', 'stok']));

        return response()->json(['success' => 'Buku berhasil diupdate!']);
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();
        return response()->json(['success' => 'Buku berhasil dihapus!']);
    }

    public function downloadTemplate()
    {
        return Excel::download(new BukuTemplateExport, 'template_buku.xlsx');
    }
}
