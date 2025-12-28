{{-- resources/views/siswa/pengembalian/index.blade.php --}}
@extends('layouts.siswa')

@section('title', 'Pengembalian Buku')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">

    <!-- Header -->
    <div class="mb-10 text-center">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Pengembalian Buku</h1>
        <p class="text-xl text-gray-600">Ajukan pengembalian buku yang sudah kamu baca</p>
    </div>

    <!-- Jika belum ada buku yang dipinjam -->
    @if($peminjaman->count() === 0)
        <div class="text-center py-20 bg-gradient-to-b from-teal-50 to-white rounded-3xl border-2 border-dashed border-teal-300">
            <i class="fas fa-book-reader text-9xl text-teal-200 mb-6"></i>
            <h3 class="text-3xl font-bold text-teal-700 mb-4">Belum ada buku yang dipinjam</h3>
            <p class="text-gray-600 text-lg max-w-md mx-auto mb-8">
                Kamu belum meminjam buku atau semua sudah dikembalikan
            </p>
            <a href="{{ route('siswa.peminjaman.create') }}"
               class="inline-flex items-center gap-3 bg-gradient-to-r from-teal-500 to-emerald-600 text-white font-bold px-8 py-4 rounded-2xl shadow-xl hover:shadow-2xl hover:from-teal-600 hover:to-emerald-700 transform hover:scale-105 transition-all duration-300 text-lg">
                <i class="fas fa-search text-2xl"></i>
                Cari Buku Baru
            </a>
        </div>
    @else
        <!-- Grid Buku yang Dipinjam -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($peminjaman as $p)
                <div class="bg-white rounded-3xl shadow-2xl hover:shadow-3xl transition-all duration-500 overflow-hidden border-4 {{ $p->status == 'dipinjam' ? 'border-blue-400' : 'border-orange-400' }} transform hover:-translate-y-2">

                    <!-- Header Status -->
                    <div class="p-6 text-white text-center font-bold text-2xl
                        {{ $p->status == 'dipinjam' ? 'bg-gradient-to-r from-blue-500 to-blue-700' : 'bg-gradient-to-r from-orange-500 to-red-600' }}">
                        <i class="fas {{ $p->status == 'dipinjam' ? 'fa-book-open' : 'fa-clock' }} mr-3"></i>
                        {{ $p->status == 'dipinjam' ? 'Sedang Dipinjam' : 'Menunggu Diterima Admin' }}
                    </div>

                    <!-- Konten Buku -->
                    <div class="p-8 space-y-6">
                        <!-- Cover + Judul -->
                        <div class="text-center">
                            <div class="w-32 h-40 mx-auto mb-6 bg-gradient-to-br from-teal-100 to-blue-100 rounded-2xl flex items-center justify-center shadow-inner">
                                <i class="fas fa-book text-6xl text-teal-600 opacity-50"></i>
                            </div>
                            <h3 class="font-bold text-2xl text-gray-800 mb-2 line-clamp-2">{{ $p->buku->judul }}</h3>
                            <p class="text-gray-600 text-lg">
                                <i class="fas fa-user-edit mr-2"></i>{{ $p->buku->pengarang }}
                            </p>
                        </div>

                        <!-- Info -->
                        <div class="bg-gray-50 rounded-2xl p-5 space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Kode Buku</span>
                                <span class="font-mono font-bold text-teal-700">{{ $p->buku->kode_buku }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Dipinjam pada</span>
                                <span class="font-bold">{{ $p->tanggal_pinjam->format('d M Y') }}</span>
                            </div>
                            @if($p->tanggal_pengajuan_kembali)
                                <div class="flex justify-between text-orange-600">
                                    <span>Diajukan kembali</span>
                                    <span class="font-bold">{{ $p->tanggal_pengajuan_kembali->format('d M Y') }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="pt-4">
                            @if($p->status === 'dipinjam')
                                <form action="{{ route('siswa.peminjaman.ajukan-kembali', $p->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="w-full bg-gradient-to-r from-orange-500 to-red-600 text-white font-bold text-xl py-5 rounded-2xl hover:from-orange-600 hover:to-red-700 transform hover:scale-105 transition-all duration-300 shadow-2xl flex items-center justify-center gap-4">
                                        <i class="fas fa-undo-alt text-3xl"></i>
                                        Ajukan Pengembalian Sekarang
                                    </button>
                                </form>
                                <p class="text-center text-gray-500 text-sm mt-4">
                                    Klik tombol di atas untuk mengajukan pengembalian
                                </p>
                            @else
                                <div class="text-center py-8">
                                    <i class="fas fa-hourglass-half text-6xl text-orange-400 mb-4"></i>
                                    <p class="text-2xl font-bold text-orange-600">Menunggu Admin</p>
                                    <p class="text-gray-600 mt-2">Buku akan segera diterima oleh petugas</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

{{-- Animasi & line-clamp --}}
@push('styles')
<style>
    .line-clamp-2 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
</style>
@endpush
