{{-- resources/views/siswa/dashboard.blade.php --}}
@extends('layouts.siswa')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-teal-50">
    <div class="max-w-7xl mx-auto px-4 py-12">

        <!-- Header Selamat Datang -->
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold text-gray-800 mb-4">
                Selamat Datang, <span class="text-teal-600">{{ auth()->user()->siswa->nama }}</span>!
            </h1>
            <p class="text-xl text-gray-600">Kelas {{ auth()->user()->siswa->kelas }} • Angkatan {{ auth()->user()->siswa->angkatan }}</p>
        </div>
{{-- Tambahkan di bagian atas dashboard siswa, setelah header selamat datang --}}

@if(auth()->user()->siswa->status_bebas_perpus === 'disetujui')
<div class="max-w-4xl mx-auto mb-12">
    <div class="bg-gradient-to-br from-emerald-600 via-teal-700 to-cyan-800 rounded-3xl shadow-3xl overflow-hidden relative border-8 border-yellow-400 transform hover:scale-105 transition-all duration-500">

        <!-- Pattern Background -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0 bg-gradient-to-tr from-white/20 to-transparent"></div>
            <i class="fas fa-graduation-cap text-9xl absolute top-10 right-10 rotate-12"></i>
            <i class="fas fa-book-open text-8xl absolute bottom-10 left-10 -rotate-12"></i>
        </div>

        <div class="relative z-10 p-10 text-white">
            <div class="text-center mb-8">
                <div class="w-32 h-32 mx-auto mb-6 bg-white rounded-full shadow-2xl overflow-hidden border-6 border-yellow-400">
                    @if(auth()->user()->siswa->foto)
                        <img src="{{ asset('storage/foto_siswa/' . auth()->user()->siswa->foto) }}"
                             alt="Foto" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-teal-500 to-emerald-600 flex items-center justify-center text-5xl font-bold text-white">
                            {{ substr(auth()->user()->siswa->nama, 0, 2) }}
                        </div>
                    @endif
                </div>

                <h2 class="text-5xl font-bold mb-3 tracking-wider drop-shadow-2xl">
                    BEBAS PERPUSTAKAAN
                </h2>
                <p class="text-2xl opacity-90">SMA Negeri 3 Metro</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-center">
                <div class="bg-white/20 backdrop-blur-lg rounded-3xl p-8 border border-white/30">
                    <p class="text-xl opacity-90 mb-2">Nama Siswa</p>
                    <p class="text-4xl font-bold">{{ auth()->user()->siswa->nama }}</p>
                </div>
                <div class="bg-white/20 backdrop-blur-lg rounded-3xl p-8 border border-white/30">
                    <p class="text-xl opacity-90 mb-2">NISN / Kelas</p>
                    <p class="text-3xl font-bold">{{ auth()->user()->siswa->nisn }}</p>
                    <p class="text-2xl">{{ auth()->user()->siswa->kelas }}</p>
                </div>
            </div>
<!-- Ganti bagian QR Code di Kartu Digital Bebas Perpus -->
<div class="text-center mt-10">
    <div class="bg-white p-8 rounded-3xl inline-block shadow-2xl border-4 border-teal-500 transform hover:scale-105 transition-all duration-300 cursor-pointer"
         onclick="window.open('{{ route('bebas-perpus.verify', auth()->user()->siswa->id) }}', '_blank')">

        <!-- QR Code + Klikable -->
        {!! QrCode::size(220)
            ->backgroundColor(255,255,255)
            ->color(13, 148, 136) // teal-700
            ->eyeColor(0, 13, 148, 136, 255, 255, 255) // mata teal
            ->style('round')
            ->eye('circle')
            ->generate(route('bebas-perpus.verify', auth()->user()->siswa->id)) !!}

        <!-- Tombol "Klik untuk Verifikasi" -->
        <div class="mt-6 bg-gradient-to-r from-teal-600 to-emerald-700 text-white font-bold px-8 py-4 rounded-2xl shadow-xl hover:shadow-2xl transition-all">
            <i class="fas fa-external-link-alt mr-3"></i>
            Klik untuk Verifikasi
        </div>
    </div>

    <p class="text-white/90 text-sm mt-4 font-medium">
        Klik QR Code atau tombol di atas untuk membuka halaman verifikasi
    </p>
</div>

            <div class="text-center mt-10 text-sm opacity-80">
                <p>Disetujui pada {{ auth()->user()->siswa->tanggal_persetujuan_bebas->format('d F Y') }}</p>
                <p>Oleh: {{ auth()->user()->siswa->admin_bebas_perpus?->username ?? 'Admin Perpus' }}</p>
            </div>
        </div>

        <!-- Ribbon "RESMI" -->
        <div class="absolute top-0 right-0 bg-yellow-400 text-teal-900 px-16 py-4 transform rotate-45 translate-x-8 translate-y-8 font-bold text-2xl shadow-2xl">
            RESMI
        </div>
    </div>

    <!-- Tombol Share / Screenshot -->
    <div class="text-center mt-6">
        <button onclick="alert('Screenshot kartu ini untuk ditunjukkan ke wali kelas/guru BK')"
                class="bg-white/90 text-teal-700 font-bold px-10 py-4 rounded-full hover:bg-white shadow-xl transition text-lg">
            <i class="fas fa-share-alt mr-3"></i>
            Bagikan Kartu Digital
        </button>
    </div>
</div>
@endif
        <!-- Profil Card -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-2xl p-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-8 text-center">
                        <i class="fas fa-user-graduate mr-3 text-teal-600"></i>
                        Ubah Kelas Saya
                    </h3>

                    <form action="{{ route('siswa.dashboard.update') }}" method="POST" class="space-y-8">
                        @csrf

                        <!-- 3 Dropdown Kelas -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                            <!-- Tingkatan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Tingkatan
                                </label>
                                <select name="tingkatan" required
                                        class="w-full px-5 py-4 border-2 border-teal-200 rounded-2xl focus:border-teal-600 focus:outline-none focus:ring-4 focus:ring-teal-100 text-lg font-medium transition-all">
                                    <option value="">-- Pilih --</option>
                                    @foreach($tingkatan as $t)
                                        <option value="{{ $t }}" {{ str_starts_with($siswa->kelas, $t) ? 'selected' : '' }}>
                                            {{ $t }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Jurusan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Jurusan
                                </label>
                                <select name="jurusan" required
                                        class="w-full px-5 py-4 border-2 border-teal-200 rounded-2xl focus:border-teal-600 focus:outline-none focus:ring-4 focus:ring-teal-100 text-lg font-medium transition-all">
                                    <option value="">-- Pilih --</option>
                                    @foreach($jurusan as $j)
                                        <option value="{{ $j }}" {{ str_contains($siswa->kelas, $j) ? 'selected' : '' }}>
                                            {{ $j }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Sub Kelas -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Kelas
                                </label>
                                <select name="sub_kelas" required
                                        class="w-full px-5 py-4 border-2 border-teal-200 rounded-2xl focus:border-teal-600 focus:outline-none focus:ring-4 focus:ring-teal-100 text-lg font-medium transition-all">
                                    <option value="">-- Pilih --</option>
                                    @foreach($subKelas as $sk)
                                        <option value="{{ $sk }}" {{ str_ends_with($siswa->kelas, $sk) ? 'selected' : '' }}>
                                            {{ $sk }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Preview Kelas -->
                        <div class="text-center">
                            <p class="text-sm text-gray-600 mb-2">Kelas kamu akan menjadi:</p>
                            <div id="previewKelas" class="inline-block bg-gradient-to-r from-teal-600 to-emerald-600 text-white font-bold text-3xl px-12 py-6 rounded-3xl shadow-2xl">
                                {{ $siswa->kelas }}
                            </div>
                        </div>

                        <!-- Tombol Simpan -->
                        <div class="text-center pt-6">
                            <button type="submit"
                                    class="bg-gradient-to-r from-emerald-600 to-teal-700 text-white font-bold text-2xl px-16 py-6 rounded-3xl hover:from-emerald-700 hover:to-teal-800 transform hover:scale-110 transition-all duration-300 shadow-2xl flex items-center gap-4 mx-auto">
                                <i class="fas fa-save text-3xl"></i>
                                Simpan Perubahan Kelas
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Statistik -->
            <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-3xl p-8 shadow-xl transform hover:scale-105 transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-lg">Sedang Dipinjam</p>
                            <p class="text-5xl font-bold mt-2">{{ $totalPinjam }}</p>
                        </div>
                        <i class="fas fa-book-open text-6xl opacity-30"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-3xl p-8 shadow-xl transform hover:scale-105 transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-lg">Sudah Dikembalikan</p>
                            <p class="text-5xl font-bold mt-2">{{ $totalKembali }}</p>
                        </div>
                        <i class="fas fa-check-circle text-6xl opacity-30"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-3xl p-8 shadow-xl transform hover:scale-105 transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-lg">Status Bebas Perpus</p>
                            <p class="text-4xl font-bold mt-2">
                                @if(auth()->user()->siswa->status_bebas_perpus == 'disetujui')
                                    <span class="text-green-300">BEBAS</span>
                                @elseif(auth()->user()->siswa->status_bebas_perpus == 'diajukan')
                                    <span class="text-yellow-300">DIAJUKAN</span>
                                @else
                                    <span class="text-gray-300">BELUM</span>
                                @endif
                            </p>
                        </div>
                        <i class="fas fa-graduation-cap text-6xl opacity-30"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Riwayat Peminjaman Terbaru -->
        <div class="bg-white rounded-3xl shadow-2xl p-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 flex items-center gap-4">
                <i class="fas fa-history text-teal-600"></i>
                Riwayat Peminjaman Terbaru
            </h2>

            @if($peminjaman->count() > 0)
                <div class="space-y-6">
                    @foreach($peminjaman as $p)
                        <div class="flex items-center justify-between p-6 bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl hover:shadow-lg transition-all">
                            <div class="flex items-center gap-6">
                                <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-book text-3xl text-teal-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-xl text-gray-800">{{ $p->buku->judul }}</h3>
                                    <p class="text-gray-600">Dipinjam: {{ $p->tanggal_pinjam->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-block px-6 py-3 rounded-full font-bold text-lg
                                    {{ $p->status == 'dipinjam' ? 'bg-blue-100 text-blue-800' :
                                       ($p->status == 'pengajuan_kembali' ? 'bg-orange-100 text-orange-800' :
                                       'bg-green-100 text-green-800') }}">
                                    {{ ucwords(str_replace('_', ' ', $p->status)) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <i class="fas fa-book-open text-8xl text-gray-300 mb-6"></i>
                    <p class="text-2xl text-gray-500">Belum ada riwayat peminjaman</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tingkatan = document.querySelector('select[name="tingkatan"]');
    const jurusan = document.querySelector('select[name="jurusan"]');
    const subKelas = document.querySelector('select[name="sub_kelas"]');
    const preview = document.getElementById('previewKelas');

    function updatePreview() {
        const t = tingkatan.value || 'X';
        const j = jurusan.value || 'IPA';
        const s = subKelas.value || '1';
        preview.textContent = `${t} ${j} ${s}`;
    }

    [tingkatan, jurusan, subKelas].forEach(el => {
        el.addEventListener('change', updatePreview);
    });

    updatePreview();
});
</script>
@endpush
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
