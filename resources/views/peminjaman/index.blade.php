{{-- resources/views/peminjaman/index.blade.php --}}
@extends('layouts.app')

@section('title', auth()->user()->role === 'admin' ? 'Validasi Peminjaman' : 'Peminjaman Saya')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">
            {{ auth()->user()->role === 'admin' ? 'Validasi Peminjaman Buku' : 'Peminjaman Saya' }}
        </h1>
        @if(auth()->user()->role === 'siswa')
            <a href="{{ route('peminjaman.create') }}"
               class="bg-teal-600 text-white px-5 py-2 rounded-lg hover:bg-teal-700 flex items-center text-sm font-medium">
                Pinjam Buku Baru
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="datatable w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        @if(auth()->user()->role === 'admin')
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
                        @endif
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buku</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl Pinjam</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($peminjaman as $p)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $loop->iteration }}</td>
                            @if(auth()->user()->role === 'admin')
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $p->siswa->nama }} <br>
                                    <span class="text-xs text-gray-500">{{ $p->siswa->kelas }}</span>
                                </td>
                            @endif
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                {{ $p->buku->judul }} <br>
                                <span class="text-xs text-gray-500">Kode: {{ $p->buku->kode_buku }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $p->tanggal_pinjam->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full
                                    @if($p->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($p->status == 'dipinjam') bg-blue-100 text-blue-800
                                    @elseif($p->status == 'pengajuan_kembali') bg-orange-100 text-orange-800
                                    @elseif($p->status == 'dikembalikan') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucwords(str_replace('_', ' ', $p->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-sm">
                                {{-- SISWA: Ajukan Kembali --}}
                                @if($p->status === 'dipinjam' && auth()->user()->role === 'siswa')
                                    <form action="{{ route('peminjaman.ajukan-kembali', $p->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 text-xs font-medium">
                                            Ajukan Kembali
                                        </button>
                                    </form>
                                @endif

                                {{-- ADMIN: Aksi Validasi --}}
                                @if(auth()->user()->role === 'admin')
                                    @if($p->status === 'pending')
                                        <div class="space-x-2">
                                            <form action="{{ route('peminjaman.approve', $p->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-800 font-medium text-sm">
                                                    Setujui
                                                </button>
                                            </form>
                                            <form action="{{ route('peminjaman.tolak', $p->id) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="text" name="catatan" placeholder="Alasan tolak" class="text-xs border rounded px-2 py-1 w-32">
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    @endif

                                    @if($p->status === 'pengajuan_kembali')
                                        <div class="space-x-2">
                                            <form action="{{ route('peminjaman.terima-kembali', $p->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 text-xs font-medium">
                                                    Terima Kembali
                                                </button>
                                            </form>
                                            <form action="{{ route('peminjaman.tolak', $p->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">
                                Belum ada data peminjaman
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
