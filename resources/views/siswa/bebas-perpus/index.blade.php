{{-- resources/views/siswa/bebas-perpus/index.blade.php --}}
@extends('layouts.siswa')

@section('title', 'Bebas Perpustakaan')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <div class="text-center mb-12">
        <h1 class="text-5xl font-bold text-gray-800 mb-4">Bebas Perpustakaan</h1>
        <p class="text-2xl text-gray-600">Pastikan semua buku sudah dikembalikan sebelum lulus</p>
    </div>

    <!-- Kartu Status Utama -->
    <div class="bg-white rounded-3xl shadow-3xl p-12 border-8 transition-all duration-500
        {{ $siswa->status_bebas_perpus == 'disetujui' ? 'border-green-500' :
           ($siswa->status_bebas_perpus == 'ditolak' ? 'border-red-500' :
           ($siswa->status_bebas_perpus == 'diajukan' ? 'border-orange-500' : 'border-gray-200')) }}">

        <div class="text-center">
            @if($siswa->status_bebas_perpus === 'disetujui')
                <!-- SUDAH BEBAS -->
                <div class="mb-10">
                    <i class="fas fa-check-circle text-10xl text-green-500 mb-8 animate-bounce"></i>
                    <h2 class="text-6xl font-bold text-green-600 mb-6">SELAMAT! KAMU BEBAS PERPUS</h2>
                    <p class="text-3xl text-gray-700">Disetujui pada {{ $siswa->tanggal_persetujuan_bebas->format('d F Y') }}</p>
                </div>
                <a href="{{ route('bebas-perpus.verify', $siswa->id) }}" target="_blank"
                   class="inline-flex items-center gap-6 bg-gradient-to-r from-green-600 to-emerald-700 text-white font-bold text-3xl px-16 py-8 rounded-3xl hover:from-green-700 hover:to-emerald-800 transform hover:scale-110 transition-all duration-500 shadow-3xl">
                    <i class="fas fa-qrcode text-5xl"></i>
                    KARTU DIGITAL BEBAS PERPUS
                </a>

            @elseif($siswa->status_bebas_perpus === 'diajukan')
                <!-- MENUNGGU -->
                <div class="mb-10">
                    <i class="fas fa-hourglass-half text-10xl text-orange-500 mb-8 animate-pulse"></i>
                    <h2 class="text-5xl font-bold text-orange-600 mb-6">Pengajuan Sedang Diproses</h2>
                    <p class="text-3xl text-gray-600">Menunggu persetujuan admin perpustakaan</p>
                </div>

            @elseif($siswa->status_bebas_perpus === 'ditolak')
                <!-- DITOLAK — BISA AJUKAN ULANG -->
                <div class="mb-10">
                    <i class="fas fa-times-circle text-10xl text-red-500 mb-8"></i>
                    <h2 class="text-5xl font-bold text-red-600 mb-6">Pengajuan Ditolak</h2>
                    <div class="bg-red-50 p-8 rounded-3xl border-4 border-red-300 mb-10">
                        <p class="text-2xl text-gray-700 italic">
                            {{ $siswa->catatan_bebas_perpus ?? 'Tidak ada catatan dari admin' }}
                        </p>
                    </div>

                    @if($belumKembali)
                        <p class="text-3xl text-red-600 mb-10">Masih ada buku yang belum dikembalikan!</p>
                        <a href="{{ route('siswa.peminjaman.index') }}"
                           class="inline-flex items-center gap-4 bg-red-600 text-white font-bold px-12 py-6 rounded-3xl hover:bg-red-700 transition text-2xl">
                            <i class="fas fa-book-reader text-4xl"></i>
                            Lihat Peminjaman Saya
                        </a>
                    @else
                        <p class="text-3xl text-gray-700 mb-12">Semua buku sudah dikembalikan. Kamu bisa mengajukan ulang!</p>
                        <form action="{{ url('/bebas-perpus/ajukan') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="bg-gradient-to-r from-orange-600 to-red-700 text-white font-bold text-4xl px-32 py-10 rounded-3xl hover:from-orange-700 hover:to-red-800 transform hover:scale-110 transition-all duration-500 shadow-3xl cursor-pointer">
                                Ajukan Ulang Sekarang
                            </button>
                        </form>
                    @endif
                </div>

            @else
                <!-- BELUM DIAJUKAN -->
                @if(!str_starts_with($siswa->kelas, 'XII'))
                    <div class="mb-10">
                        <i class="fas fa-lock text-10xl text-gray-400 mb-8"></i>
                        <h2 class="text-5xl font-bold text-gray-600 mb-6">Belum Waktunya</h2>
                        <p class="text-3xl text-gray-500">Hanya siswa kelas XII yang bisa mengajukan</p>
                        <p class="text-2xl text-gray-500 mt-6">Kelas kamu: {{ $siswa->kelas }}</p>
                    </div>
                @elseif($belumKembali)
                    <div class="mb-10">
                        <i class="fas fa-exclamation-triangle text-10xl text-red-500 mb-8"></i>
                        <h2 class="text-5xl font-bold text-red-600 mb-6">Belum Bisa Mengajukan</h2>
                        <p class="text-3xl text-gray-700">Masih ada buku yang belum dikembalikan!</p>
                        <a href="{{ route('siswa.peminjaman.index') }}"
                           class="mt-10 inline-flex items-center gap-4 bg-red-600 text-white font-bold px-12 py-6 rounded-3xl hover:bg-red-700 transition text-2xl">
                            <i class="fas fa-book-reader text-4xl"></i>
                            Lihat Peminjaman Saya
                        </a>
                    </div>
                @else
                    <div class="mb-10">
                        <i class="fas fa-check-double text-10xl text-emerald-500 mb-8"></i>
                        <h2 class="text-5xl font-bold text-emerald-600 mb-6">Siap Bebas Perpus!</h2>
                        <p class="text-3xl text-gray-700 mb-12">Semua buku sudah dikembalikan</p>

                        <form action="{{ url('/bebas-perpus/ajukan') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="bg-gradient-to-r from-emerald-600 to-teal-700 text-white font-bold text-4xl px-24 py-10 rounded-3xl hover:from-emerald-700 hover:to-teal-800 transform hover:scale-110 transition-all duration-500 shadow-3xl cursor-pointer">
                                Ajukan Bebas Perpustakaan Sekarang
                            </button>
                        </form>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .animate-bounce { animation: bounce 2s infinite; }
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-30px); }
    }
</style>
@endpush
