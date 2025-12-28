{{-- resources/views/admin/bebas-perpus/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Validasi Bebas Perpustakaan')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">

    <!-- Header + Badge -->
    <div class="text-center mb-12 relative">
        <h1 class="text-6xl font-bold text-gray-800 mb-4">Validasi Bebas Perpustakaan</h1>
        <p class="text-l text-gray-600">Kelola pengajuan Bebas Perpustakaan</p>

        @if($pengajuan->where('status_bebas_perpus', 'diajukan')->count() > 0)
            <div class="absolute top-0 right-0 bg-red-600 text-white px-8 py-4 rounded-full text-l font-bold shadow-2xl animate-pulse">
                {{ $pengajuan->where('status_bebas_perpus', 'diajukan')->count() }} Menunggu
            </div>
        @endif
    </div>

    <!-- Filter Cepat -->
    <div class="flex justify-center gap-6 mb-12">
        <a href="{{ route('admin.bebas-perpus.index') }}"
           class="px-10 py-5 rounded-2xl font-bold text-xl transition-all shadow-lg {{ !request('status') ? 'bg-gradient-to-r from-teal-600 to-emerald-700 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
            Semua
        </a>
        <a href="{{ route('admin.bebas-perpus.index', ['status' => 'diajukan']) }}"
           class="px-10 py-5 rounded-2xl font-bold text-xl transition-all shadow-lg relative {{ request('status') == 'diajukan' ? 'bg-gradient-to-r from-orange-500 to-red-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
            Menunggu Validasi
            @if($pengajuan->where('status_bebas_perpus', 'diajukan')->count() > 0)
                <span class="absolute -top-3 -right-3 bg-red-600 text-white text-xs font-bold rounded-full h-10 w-10 flex items-center justify-center animate-ping">!</span>
            @endif
        </a>
        <a href="{{ route('admin.bebas-perpus.index', ['status' => 'disetujui']) }}"
           class="px-10 py-5 rounded-2xl font-bold text-xl transition-all shadow-lg {{ request('status') == 'disetujui' ? 'bg-gradient-to-r from-green-500 to-emerald-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
            Sudah Disetujui
        </a>
    </div>

    @if($pengajuan->count() == 0)
        <div class="text-center py-32 bg-gradient-to-b from-gray-50 to-white rounded-3xl border-4 border-dashed border-gray-300">
            <i class="fas fa-check-double text-12xl text-gray-300 mb-10"></i>
            <h3 class="text-5xl font-bold text-gray-700 mb-6">Semua Sudah Diproses!</h3>
            <p class="text-l text-gray-500">Tidak ada pengajuan bebas perpus saat ini</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-12">
            @foreach($pengajuan as $s)
                <div class="bg-white rounded-3xl shadow-3xl overflow-hidden border-10 {{ $s->status_bebas_perpus == 'diajukan' ? 'border-orange-500' : 'border-green-500' }} transform hover:scale-105 transition-all duration-500">

                    <!-- Header Status -->
                    <div class="p-8 text-white text-center font-bold text-3xl
                        {{ $s->status_bebas_perpus == 'diajukan' ? 'bg-gradient-to-r from-orange-500 to-red-600' : 'bg-gradient-to-r from-green-500 to-emerald-600' }}">
                        <i class="fas {{ $s->status_bebas_perpus == 'diajukan' ? 'fa-hourglass-half' : 'fa-award' }} text-7xl mb-4"></i>
                        <p>{{ $s->status_bebas_perpus == 'diajukan' ? 'MENUNGGU' : 'BEBAS!' }}</p>
                    </div>

                    <div class="p-10 space-y-8">
                        <!-- Profil Siswa -->
                        <div class="text-center">
                            <div class="w-36 h-36 mx-auto mb-6 bg-gradient-to-br from-teal-500 to-emerald-600 rounded-full flex items-center justify-center text-white text-5xl font-bold shadow-3xl border-8 border-white">
                                {{ substr($s->nama, 0, 2) }}
                            </div>
                            <h3 class="text-4xl font-bold text-gray-800">{{ $s->nama }}</h3>
                            <p class="text-l text-gray-600 mt-2">{{ $s->kelas }}</p>
                            <p class="text-xl text-gray-500 font-mono mt-1">{{ $s->nisn }}</p>
                        </div>

                        <!-- QR Code KLIKABLE -->
                        <div class="bg-gray-50 p-8 rounded-3xl border-4 border-dashed border-teal-400 text-center cursor-pointer transform hover:scale-110 transition-all duration-300"
                             onclick="window.open('{{ route('bebas-perpus.verify', $s->id) }}', '_blank')">
                            <div class="bg-white p-6 rounded-3xl inline-block shadow-2xl">
                                {!! QrCode::size(180)
                                    ->backgroundColor(255,255,255)
                                    ->color(13, 148, 136)           // teal-700
                                    ->eyeColor(0, 13, 148, 136, 255, 255, 255)
                                    ->style('round')
                                    ->eye('circle')
                                    ->margin(2)
                                    ->generate(route('bebas-perpus.verify', $s->id)) !!}
                            </div>
                            <div class="mt-6 bg-gradient-to-r from-teal-600 to-emerald-700 text-white font-bold text-l px-12 py-6 rounded-3xl shadow-2xl">
                                KLIK UNTUK VERIFIKASI
                            </div>
                            <p class="text-gray-600 mt-4 font-medium">Bisa discan atau diklik langsung</p>
                        </div>

                        <!-- Tombol Aksi Admin -->
                        <div class="space-y-5">
                            @if($s->status_bebas_perpus == 'diajukan')
                                <div class="grid grid-cols-2 gap-6">
                                    <form action="{{ route('bebas-perpus.proses', $s->id) }}" method="POST">
                                        @csrf <input type="hidden" name="aksi" value="setujui">
                                        <button type="submit"
                                                class="w-full bg-gradient-to-r from-emerald-600 to-teal-700 text-white font-bold text-l py-6 rounded-3xl hover:from-emerald-700 hover:to-teal-800 transform hover:scale-110 transition-all shadow-3xl flex items-center justify-center gap-4">
                                            <i class="fas fa-check-double text-4xl"></i>
                                            SETUJUI
                                        </button>
                                    </form>
                                    <form action="{{ route('bebas-perpus.proses', $s->id) }}" method="POST">
                                        @csrf <input type="hidden" name="aksi" value="tolak">
                                        <input type="text" name="catatan" placeholder="Alasan tolak..." class="w-full px-6 py-5 border-4 border-red-300 rounded-3xl text-lg focus:border-red-500 mb-3">
                                        <button type="submit"
                                                class="w-full bg-gradient-to-r from-red-600 to-rose-700 text-white font-bold text-l py-6 rounded-3xl hover:from-red-700 hover:to-rose-800 transform hover:scale-110 transition-all shadow-3xl flex items-center justify-center gap-4">
                                            <i class="fas fa-times-circle text-4xl"></i>
                                            TOLAK
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="text-center bg-gradient-to-r from-green-600 to-emerald-700 text-white font-bold text-3xl py-8 rounded-3xl shadow-3xl">
                                    SUDAH BEBAS PERPUSTAKAAN
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
