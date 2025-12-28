@extends('layouts.admin')
@section('title', 'Dashboard Admin Perpustakaan')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">

    <!-- Header -->
    <div class="mb-12 text-center">
        <h1 class="text-5xl font-bold text-gray-800 mb-4">Dashboard Perpustakaan</h1>
        <p class="text-2xl text-gray-600">SMA Negeri 3 Metro</p>
    </div>

    <!-- Statistik Utama -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
        <div class="bg-gradient-to-r from-teal-500 to-emerald-600 text-white rounded-3xl p-8 shadow-2xl transform hover:scale-105 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-teal-100 text-lg">Total Buku</p>
                    <p class="text-5xl font-bold mt-2">{{ $totalBuku }}</p>
                </div>
                <i class="fas fa-book text-7xl opacity-30"></i>
            </div>
        </div>
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-3xl p-8 shadow-2xl transform hover:scale-105 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-lg">Sedang Dipinjam</p>
                    <p class="text-5xl font-bold mt-2">{{ $sedangDipinjam }}</p>
                </div>
                <i class="fas fa-book-reader text-7xl opacity-30"></i>
            </div>
        </div>
        <div class="bg-gradient-to-r from-orange-500 to-red-600 text-white rounded-3xl p-8 shadow-2xl transform hover:scale-105 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-lg">Pengajuan Kembali</p>
                    <p class="text-5xl font-bold mt-2">{{ $pengajuanKembali }}</p>
                </div>
                <i class="fas fa-undo text-7xl opacity-30"></i>
            </div>
        </div>
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-3xl p-8 shadow-2xl transform hover:scale-105 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-lg">Bebas Perpus</p>
                    <p class="text-5xl font-bold mt-2">{{ $bebasPerpus }}</p>
                </div>
                <i class="fas fa-graduation-cap text-7xl opacity-30"></i>
            </div>
        </div>
    </div>

    <!-- Chart Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-12">
        <!-- Peminjaman per Bulan -->
        <div class="bg-white rounded-3xl shadow-2xl p-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Peminjaman per Bulan</h2>
            <canvas id="chartPeminjaman" height="120"></canvas>
        </div>

        <!-- Buku Terpopuler -->
        <div class="bg-white rounded-3xl shadow-2xl p-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Buku Terpopuler</h2>
            <canvas id="chartBukuPopuler" height="120"></canvas>
        </div>

        <!-- Status Peminjaman -->
        <div class="bg-white rounded-3xl shadow-2xl p-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Status Peminjaman</h2>
            <canvas id="chartStatus" height="120"></canvas>
        </div>

        <!-- Bebas Perpus per Kelas -->
        <div class="bg-white rounded-3xl shadow-2xl p-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Bebas Perpus per Kelas</h2>
            <canvas id="chartBebasPerpus" height="120"></canvas>
        </div>
    </div>

    <!-- Aktivitas & Pengajuan -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        <!-- Aktivitas Terbaru -->
        <div class="bg-white rounded-3xl shadow-2xl p-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Aktivitas Terbaru</h2>
            <div class="space-y-6">
                @foreach($aktivitasTerbaru as $a)
                    <div class="flex items-center justify-between p-6 bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl hover:shadow-lg transition-all">
                        <div class="flex items-center gap-6">
                            <div class="w-14 h-14 bg-teal-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-book text-3xl text-teal-600"></i>
                            </div>
                            <div>
                                <p class="font-bold text-lg text-gray-800">{{ $a->siswa->nama }}</p>
                                <p class="text-gray-600">{{ $a->buku->judul }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">{{ $a->tanggal_pinjam->format('d/m/Y') }}</p>
                            <span class="inline-block px-4 py-2 rounded-full text-xs font-bold mt-2
                                {{ $a->status == 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                   ($a->status == 'dipinjam' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800') }}">
                                {{ ucwords(str_replace('_', ' ', $a->status)) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Pengajuan Bebas Perpus -->
        <div class="bg-white rounded-3xl shadow-2xl p-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Pengajuan Bebas Perpus</h2>
            <div class="space-y-6">
                @foreach($bebasPerpusPending as $s)
                    <div class="flex items-center justify-between p-6 bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl hover:shadow-lg transition-all border-2 border-purple-200">
                        <div class="flex items-center gap-6">
                            <div class="w-14 h-14 bg-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                                {{ substr($s->nama, 0, 2) }}
                            </div>
                            <div>
                                <p class="font-bold text-lg text-gray-800">{{ $s->nama }}</p>
                                <p class="text-gray-600">{{ $s->kelas }} • {{ $s->nisn }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.bebas-perpus.index') }}"
                           class="bg-purple-600 text-white px-6 py-3 rounded-xl hover:bg-purple-700 font-medium shadow-lg">
                            Proses
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- CHART.JS SCRIPT --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx1 = document.getElementById('chartPeminjaman').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: @json($chartPeminjaman['labels']),
            datasets: [{
                label: 'Peminjaman',
                data: @json($chartPeminjaman['data']),
                borderColor: '#14b8a6',
                backgroundColor: 'rgba(20, 184, 166, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'top' } } }
    });

    const ctx2 = document.getElementById('chartBukuPopuler').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: @json($chartBukuPopuler['labels']),
            datasets: [{
                label: 'Jumlah Dipinjam',
                data: @json($chartBukuPopuler['data']),
                backgroundColor: ['#3b82f6', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981']
            }]
        },
        options: { responsive: true }
    });

    const ctx3 = document.getElementById('chartStatus').getContext('2d');
    new Chart(ctx3, {
        type: 'pie',
        data: {
            labels: ['Pending', 'Dipinjam', 'Pengajuan Kembali', 'Dikembalikan'],
            datasets: [{
                data: @json($chartStatus),
                backgroundColor: ['#f59e0b', '#3b82f6', '#f97316', '#10b981']
            }]
        },
        options: { responsive: true }
    });

    const ctx4 = document.getElementById('chartBebasPerpus').getContext('2d');
    new Chart(ctx4, {
        type: 'doughnut',
        data: {
            labels: @json($chartBebasPerpus['labels']),
            datasets: [{
                data: @json($chartBebasPerpus['data']),
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444']
            }]
        },
        options: { responsive: true }
    });
});
</script>
@endpush
@endsection
