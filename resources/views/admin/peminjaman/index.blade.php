@extends('layouts.admin')
@section('title', 'Validasi Peminjaman Buku')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">

    <!-- Header -->
    <div class="mb-12 text-center">
        <h1 class="text-5xl font-bold text-gray-800 mb-4">Validasi Peminjaman Buku</h1>
        <p class="text-2xl text-gray-600">Cari dan proses peminjaman siswa dengan cepat</p>
    </div>

    <!-- Search Bar + Filter -->
    <div class="mb-12">
        <!-- Search Bar -->
        <div class="max-w-4xl mx-auto mb-8">
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Cari nama siswa, NISN, kelas, judul buku, kode buku..."
                       class="w-full px-10 py-6 text-2xl border-4 border-teal-300 rounded-full focus:border-teal-600 focus:outline-none focus:ring-8 focus:ring-teal-100 transition-all shadow-2xl bg-white">
                <div class="absolute right-8 top-7 text-teal-600">
                    <i class="fas fa-search text-3xl"></i>
                </div>
            </div>
            <p class="text-center text-gray-500 mt-4 text-lg">Ketik untuk mencari secara real-time</p>
        </div>

        <!-- Filter Status -->
        <div class="flex justify-center gap-6 mb-8">
            <a href="{{ route('admin.peminjaman.index') }}"
               class="px-10 py-5 rounded-2xl font-bold text-xl transition-all shadow-lg {{ !request('status') ? 'bg-gradient-to-r from-teal-600 to-emerald-700 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Semua
            </a>
            <a href="{{ route('admin.peminjaman.index', ['status' => 'pending']) }}"
               class="px-10 py-5 rounded-2xl font-bold text-xl transition-all shadow-lg relative {{ request('status') == 'pending' ? 'bg-gradient-to-r from-yellow-500 to-amber-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Menunggu Persetujuan
                @if($stats['pending'] > 0)
                    <span class="absolute -top-3 -right-3 bg-red-600 text-white text-xs font-bold rounded-full h-10 w-10 flex items-center justify-center animate-pulse shadow-2xl">
                        {{ $stats['pending'] }}
                    </span>
                @endif
            </a>
            <a href="{{ route('admin.peminjaman.index', ['status' => 'pengajuan_kembali']) }}"
               class="px-10 py-5 rounded-2xl font-bold text-xl transition-all shadow-lg relative {{ request('status') == 'pengajuan_kembali' ? 'bg-gradient-to-r from-orange-500 to-red-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Menunggu Dikembalikan
                @if($stats['pengajuan_kembali'] > 0)
                    <span class="absolute -top-3 -right-3 bg-red-600 text-white text-xs font-bold rounded-full h-10 w-10 flex items-center justify-center animate-pulse shadow-2xl">
                        {{ $stats['pengajuan_kembali'] }}
                    </span>
                @endif
            </a>
        </div>

        <!-- Jumlah Hasil -->
        <div class="text-center">
            <p class="text-xl text-gray-600">
                Menampilkan <span id="resultCount" class="font-bold text-teal-600">{{ $peminjaman->count() }}</span> permintaan
            </p>
        </div>
    </div>

    <!-- Grid Kartu dengan Live Search -->
    <div id="peminjamanContainer" class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-12">
        @foreach($peminjaman as $p)
            <div class="peminjaman-card bg-white rounded-3xl shadow-2xl hover:shadow-3xl transition-all duration-500 overflow-hidden border-8
                {{ $p->status == 'pending' ? 'border-yellow-500' : 'border-orange-500' }}
                transform hover:-translate-y-3"
                 data-search="{{ strtolower($p->siswa->nama . ' ' . $p->siswa->nisn . ' ' . $p->siswa->kelas . ' ' . $p->buku->judul . ' ' . $p->buku->pengarang . ' ' . $p->buku->kode_buku) }}">

                <!-- Header Status -->
                <div class="p-8 text-white text-center font-bold text-3l
                    {{ $p->status == 'pending' ? 'bg-gradient-to-r from-yellow-500 to-amber-600' : 'bg-gradient-to-r from-orange-500 to-red-600' }}">
                    <i class="fas {{ $p->status == 'pending' ? 'fa-hourglass-half' : 'fa-undo-alt' }} text-6xl mb-4"></i>
                    <p>{{ $p->status == 'pending' ? 'MENUNGGU PERSETUJUAN' : 'PENGAJUAN PENGEMBALIAN' }}</p>
                </div>

                <div class="p-10 space-y-8">
                    <!-- Info Siswa -->
                    <div class="flex items-center gap-6 bg-gradient-to-r from-teal-50 to-cyan-50 p-6 rounded-3xl border-2 border-teal-200 shadow-lg">
                        <div class="w-20 h-20 bg-teal-600 rounded-full flex items-center justify-center text-white font-bold text-3l shadow-2xl">
                            {{ substr($p->siswa->nama, 0, 2) }}
                        </div>
                        <div>
                            <p class="font-bold text-2xl text-gray-800">{{ $p->siswa->nama }}</p>
                            <p class="text-xl text-gray-600">{{ $p->siswa->kelas }} • {{ $p->siswa->nisn }}</p>
                        </div>
                    </div>

                    <!-- Info Buku -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-8 rounded-3xl border-2 border-blue-200 shadow-lg">
                        <h3 class="font-bold text-3l text-gray-800 mb-4">{{ $p->buku->judul }}</h3>
                        <div class="text-xl space-y-3">
                            <p><i class="fas fa-user-edit mr-4 text-indigo-600"></i>{{ $p->buku->pengarang }}</p>
                            <p><i class="fas fa-barcode mr-4 text-indigo-600"></i>{{ $p->buku->kode_buku }}</p>
                        </div>
                    </div>

                    <!-- Tanggal -->
                    <div class="grid grid-cols-2 gap-6 text-center">
                        <div class="p-6 rounded-3xl">
                            <p class="text-gray-600 text-lg">Dipinjam</p>
                            <p class="font-bold text-3l text-gray-800 mt-2">{{ $p->tanggal_pinjam->format('d/m/Y') }}</p>
                        </div>
                        @if($p->tanggal_pengajuan_kembali)
                        <div class="bg-orange-50 p-6 rounded-3xl border-2 border-orange-300 shadow-inner">
                            <p class="text-orange-700 text-lg">Diajukan Kembali</p>
                            <p class="font-bold text-3l text-orange-800 mt-2">{{ $p->tanggal_pengajuan_kembali->format('d/m/Y') }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Aksi Admin -->
                    <div class="pt-8 space-y-6 border-t-4 border-gray-200">
                        @if($p->status === 'pending')
                            <div class="grid grid-cols-2 gap-6">
                                <form action="{{ route('peminjaman.approve', $p->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="w-full bg-gradient-to-r from-emerald-600 to-teal-700 text-white font-bold text-2xl py-6 rounded-3xl hover:from-emerald-700 hover:to-teal-800 transform hover:scale-110 transition-all shadow-3xl flex items-center justify-center gap-4">
                                        <i class="fas fa-check-double text-4xl"></i>
                                        SETUJUI
                                    </button>
                                </form>
                                <form action="{{ route('peminjaman.tolak', $p->id) }}" method="POST">
                                    @csrf
                                    <input type="text" name="catatan" placeholder="Alasan penolakan..." class="w-full px-6 py-5 border-4 border-red-300 rounded-3xl text-lg focus:border-red-500 mb-4">
                                    <button type="submit"
                                            class="w-full bg-gradient-to-r from-red-600 to-rose-700 text-white font-bold text-2xl py-6 rounded-3xl hover:from-red-700 hover:to-rose-800 transform hover:scale-110 transition-all shadow-3xl flex items-center justify-center gap-4">
                                        <i class="fas fa-times-circle text-4xl"></i>
                                        TOLAK
                                    </button>
                                </form>
                            </div>
                        @endif

                        @if($p->status === 'pengajuan_kembali')
                            {{-- Ganti dengan Grid 2 Kolom (Terima & Tolak) --}}
                            <div class="grid grid-cols-2 gap-6">
                                
                                {{-- 1. TOMBOL TERIMA (Hijau) --}}
                                <form action="{{ route('peminjaman.terima-kembali', $p->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="w-full bg-gradient-to-r from-emerald-600 to-teal-700 text-white font-bold text-2xl py-6 rounded-3xl hover:from-emerald-700 hover:to-teal-800 transform hover:scale-110 transition-all shadow-3xl flex items-center justify-center gap-4">
                                        <i class="fas fa-book-reader text-4xl"></i>
                                        TERIMA
                                    </button>
                                </form>

                                {{-- 2. TOMBOL TOLAK (Merah) --}}
                                <form action="{{ route('peminjaman.tolak', $p->id) }}" method="POST">
                                    @csrf
                                    {{-- Input Alasan --}}
                                    <input type="text" name="catatan" placeholder="Alasan penolakan..." class="w-full px-6 py-5 border-4 border-red-300 rounded-3xl text-lg focus:border-red-500 mb-4" required>
                                    
                                    {{-- Tombol Submit --}}
                                    <button type="submit"
                                            class="w-full bg-gradient-to-r from-red-600 to-rose-700 text-white font-bold text-2xl py-6 rounded-3xl hover:from-red-700 hover:to-rose-800 transform hover:scale-110 transition-all shadow-3xl flex items-center justify-center gap-4">
                                        <i class="fas fa-times-circle text-4xl"></i>
                                        TOLAK
                                    </button>
                                </form>
                            </div>
                        @endif

                        @if($p->catatan_admin)
                            <div class="bg-gray-50 p-6 rounded-3xl border-l-6 border-teal-500">
                                <p class="font-medium text-gray-700 mb-2">Catatan Admin:</p>
                                <p class="text-gray-600 italic text-lg">{{ $p->catatan_admin }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Empty State Saat Search Tidak Ada Hasil -->
    <div id="noResult" class="hidden text-center py-32">
        <i class="fas fa-search text-12xl text-gray-300 mb-10"></i>
        <h3 class="text-5xl font-bold text-gray-700 mb-6">Tidak Ditemukan</h3>
        <p class="text-3xl text-gray-500">Coba kata kunci lain atau hapus filter</p>
    </div>
</div>

{{-- LIVE SEARCH SCRIPT --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const cards = document.querySelectorAll('.peminjaman-card');
    const container = document.getElementById('peminjamanContainer');
    const noResult = document.getElementById('noResult');
    const resultCount = document.getElementById('resultCount');

    searchInput.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
        let visible = 0;

        cards.forEach(card => {
            const text = card.dataset.search;
            if (query === '' || text.includes(query)) {
                card.style.display = 'block';
                visible++;
            } else {
                card.style.display = 'none';
            }
        });

        resultCount.textContent = visible;

        if (visible === 0 && query !== '') {
            container.classList.add('hidden');
            noResult.classList.remove('hidden');
        } else {
            container.classList.remove('hidden');
            noResult.classList.add('hidden');
        }
    });
});
</script>
@endpush
@endsection
