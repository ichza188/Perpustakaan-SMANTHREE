@extends('layouts.admin')

@section('title', 'Tambah Data Buku')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Tambah Data Buku</h1>
        <p class="text-gray-600 mt-2">Input buku baru secara manual atau import via Excel.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-r shadow-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-r shadow-sm">
            {!! session('error') !!}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 h-fit">
            <div class="flex items-center gap-3 mb-6 border-b pb-4 border-gray-100">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                    <i class="fas fa-book-medical text-lg"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Input Buku Manual</h2>
            </div>
            
            <form action="{{ route('buku.store') }}" method="POST">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Buku</label>
                        <input type="text" name="kode_buku" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500" required placeholder="Contoh: B001">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Judul Buku</label>
                        <input type="text" name="judul" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500" required placeholder="Contoh: Belajar Laravel">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pengarang</label>
                        <input type="text" name="pengarang" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500" required placeholder="Nama Penulis">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Stok Buku</label>
                        <input type="number" name="stok" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500" required placeholder="Jumlah Stok" min="0">
                    </div>
                </div>
                <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-between">
                    <a href="{{ route('buku.index') }}" class="text-gray-500 hover:text-gray-700 font-medium">Batal</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-md transform hover:scale-105 transition-all">
                        <i class="fas fa-save mr-2"></i> Simpan Buku
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-gray-50 p-8 rounded-2xl shadow-inner border border-gray-200 h-fit">
            <div class="flex items-center gap-3 mb-6 border-b pb-4 border-gray-200">
                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                    <i class="fas fa-file-excel text-lg"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Import Excel (Massal)</h2>
            </div>
            
            <form action="{{ route('buku.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Upload File (.xlsx / .xls)</label>
                    <input name="excel_file" type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" accept=".xlsx,.xls" required />
                </div>
                
                <div class="flex flex-col gap-3">
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-xl shadow flex items-center justify-center gap-2">
                        <i class="fas fa-upload"></i> Proses Import
                    </button>
                    <a href="{{ route('admin.buku.download-template') }}" class="w-full bg-white hover:bg-gray-100 text-gray-700 font-semibold py-3 px-4 rounded-xl border border-gray-300 shadow-sm flex items-center justify-center gap-2">
                        <i class="fas fa-download text-gray-500"></i> Download Template
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
