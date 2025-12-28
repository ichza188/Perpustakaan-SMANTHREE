@extends('layouts.admin')

@section('title', 'Tambah Data Siswa')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Tambah Data Siswa</h1>
        <p class="text-gray-600 mt-2">Pilih metode input manual atau import massal via Excel.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-r shadow-sm">
            <p class="font-bold">Berhasil!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-r shadow-sm">
            <p class="font-bold">Gagal!</p>
            <p>{!! session('error') !!}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 h-fit">
            <div class="flex items-center gap-3 mb-6 border-b pb-4 border-gray-100">
                <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-600">
                    <i class="fas fa-user-plus text-lg"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Input Manual</h2>
            </div>
            
            <form action="{{ route('admin.siswa.store') }}" method="POST">
                @csrf
                
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="nama" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors" required placeholder="Contoh: Budi Santoso">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">NISN <span class="text-xs font-normal text-gray-500">(Akan menjadi Username)</span></label>
                        <input type="number" name="nisn" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors" required placeholder="Contoh: 0012345678">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kelas</label>
                            <input type="text" name="kelas" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors" required placeholder="XII IPA 1">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Angkatan</label>
                            <input type="number" name="angkatan" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors" required placeholder="2025" value="{{ date('Y') }}">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors" required>
                        <p class="text-xs text-gray-500 mt-1">*Password default akan diambil dari tanggal lahir (Format: YYYYMMDD)</p>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-between">
                    <a href="{{ route('admin.siswa.index') }}" class="text-gray-500 hover:text-gray-700 font-medium">Batal</a>
                    <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-6 rounded-xl shadow-md transition-all transform hover:scale-105">
                        <i class="fas fa-save mr-2"></i> Simpan Siswa
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
            
            <form action="{{ route('admin.siswa.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Upload File (.xlsx / .xls)</label>
                    <div class="flex items-center justify-center w-full">
                        <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-100 transition-colors">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span></p>
                                <p class="text-xs text-gray-500">XLSX atau XLS</p>
                            </div>
                            <input id="dropzone-file" name="excel_file" type="file" class="hidden" accept=".xlsx,.xls" required />
                        </label>
                    </div>
                    @error('excel_file')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-800">
                    <p class="font-bold mb-2 flex items-center"><i class="fas fa-info-circle mr-2"></i> Ketentuan Kolom Excel:</p>
                    <ul class="list-disc list-inside space-y-1 ml-1 text-yellow-700">
                        <li><strong>nama</strong>: Nama Lengkap</li>
                        <li><strong>tanggal_lahir</strong>: 15/05/2007</li>
                        <li><strong>angkatan</strong>: 2024</li>
                        <li><strong>nisn</strong>: 10 digit (Unik)</li>
                        <li><strong>kelas</strong>: XII IPA 1</li>
                    </ul>
                </div>
                
                <div class="flex flex-col gap-3">
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-xl shadow transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-upload"></i> Proses Import
                    </button>
                    
                    <a href="{{ route('admin.siswa.download-template') }}" class="w-full bg-white hover:bg-gray-100 text-gray-700 font-semibold py-3 px-4 rounded-xl border border-gray-300 shadow-sm transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-download text-gray-500"></i> Download Template Excel
                    </a>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection