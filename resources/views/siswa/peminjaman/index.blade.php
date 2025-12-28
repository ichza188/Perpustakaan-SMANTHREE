{{-- resources/views/siswa/peminjaman/index.blade.php --}}
@extends('layouts.siswa')

@section('title', 'Peminjaman Saya')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">

    <!-- Header + Tombol Pinjam Baru -->
    <div class="mb-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Peminjaman Saya</h1>
            <p class="text-gray-600 mt-1">Lihat status buku yang sedang kamu pinjam</p>
        </div>
        <a href="{{ route('siswa.peminjaman.create') }}"
           class="inline-flex items-center gap-3 bg-gradient-to-r from-teal-500 to-teal-600 text-white font-bold px-6 py-4 rounded-2xl shadow-lg hover:shadow-xl hover:from-teal-600 hover:to-teal-700 transform hover:scale-105 transition-all duration-200">
            <i class="fas fa-plus-circle text-xl"></i>
            Pinjam Buku Baru
        </a>
    </div>

    <!-- Jika belum ada peminjaman -->
    @if($peminjaman->count() === 0)
        <div class="text-center py-20 bg-gradient-to-b from-gray-50 to-white rounded-3xl border-2 border-dashed border-gray-300">
            <i class="fas fa-book-open text-8xl text-gray-300 mb-6"></i>
            <h3 class="text-2xl font-semibold text-gray-700 mb-3">Belum ada peminjaman</h3>
            <p class="text-gray-500 max-w-md mx-auto">Kamu belum pernah meminjam buku. Yuk cari buku yang ingin kamu baca!</p>
            <a href="{{ route('siswa.peminjaman.create') }}"
               class="mt-6 inline-block bg-teal-600 text-white px-8 py-3 rounded-full font-medium hover:bg-teal-700 transition">
                Mulai Pinjam Buku
            </a>
        </div>
    @else
        <!-- Grid Kartu Peminjaman (Super Modern!) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($peminjaman as $p)
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 transform hover:-translate-y-1">
                    <!-- Header Kartu dengan Warna Status -->
                    <div class="p-5 text-white font-bold text-center text-lg
                        @if($p->status == 'pending') bg-gradient-to-r from-yellow-500 to-amber-600
-warning
                        @elseif($p->status == 'dipinjam') bg-gradient-to-r from-blue-500 to-blue-700
                        @elseif($p->status == 'pengajuan_kembali') bg-gradient-to-r from-orange-500 to-red-600
                        @elseif($p->status == 'dikembalikan') bg-gradient-to-r from-green-500 to-emerald-600
                        @else bg-gradient-to-r from-red-500 to-rose-600 @endif">
                        {{ ucwords(str_replace('_', ' ', $p->status)) }}
                    </div>

                    <!-- Konten Kartu -->
                    <div class="p-6">
                        <h3 class="font-bold text-xl text-gray-800 mb-3 line-clamp-2">{{ $p->buku->judul }}</h3>

                        <div class="space-y-3 text-sm">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-user-edit w-5 mr-3 text-teal-600"></i>
                                <span>{{ $p->buku->pengarang }}</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-barcode w-5 mr-3 text-teal-600"></i>
                                <span class="font-mono">{{ $p->buku->kode_buku }}</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-calendar-alt w-5 mr-3 text-teal-600"></i>
                                <span>Dipinjam: {{ $p->tanggal_pinjam->format('d F Y') }}</span>
                            </div>
                        </div>

                        <!-- Aksi -->
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            @if($p->status === 'dipinjam')
                                <form action="{{ route('siswa.peminjaman.ajukan-kembali', $p->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="w-full bg-gradient-to-r from-orange-500 to-red-600 text-white font-bold py-3 px-6 rounded-xl hover:from-orange-600 hover:to-red-700 transform hover:scale-105 transition-all duration-200 shadow-md">
                                        Ajukan Pengembalian
                                    </button>
                                </form>
                            @elseif($p->status === 'pengajuan_kembali')
                                <div class="text-center">
                                    <i class="fas fa-clock text-4xl text-orange-500 mb-3"></i>
                                    <p class="text-orange-700 font-medium">Menunggu admin menerima buku</p>
                                </div>
                            @elseif($p->status === 'dikembalikan')
                                <div class="text-center text-green-600">
                                    <i class="fas fa-check-circle text-5xl mb-3"></i>
                                    <p class="font-bold">Buku telah dikembalikan</p>
                                </div>
                            @elseif($p->status === 'pending')
                                <div class="text-center text-yellow-600">
                                    <i class="fas fa-hourglass-half text-4xl mb-3"></i>
                                    <p class="font-medium">Menunggu persetujuan admin</p>
                                </div>
                            @else
                                <div class="text-center text-red-600">
                                    <i class="fas fa-times-circle text-5xl mb-3"></i>
                                    <p class="font-bold">Peminjaman ditolak</p>
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

{{-- Tambahkan line-clamp untuk teks panjang --}}
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
