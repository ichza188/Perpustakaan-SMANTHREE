{{-- resources/views/peminjaman/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Pinjam Buku Baru')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-2xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Pinjam Buku Baru</h1>
        <p class="text-gray-600 mt-2">Pilih buku yang ingin dipinjam</p>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6">
        <form action="{{ route('peminjaman.store') }}" method="POST">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Buku</label>
                <select name="buku_id" required class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    <option value="">-- Pilih Buku --</option>
                    @foreach($buku as $b)
                        <option value="{{ $b->id }}">
                            {{ $b->judul }} ({{ $b->pengarang }}) — Stok: {{ $b->stokTersedia() }}
                        </option>
                    @endforeach
                </select>
                @error('buku_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex space-x-3">
                <button type="submit"
                        class="bg-teal-600 text-white px-6 py-3 rounded-lg hover:bg-teal-700 font-medium">
                    Ajukan Peminjaman
                </button>
                <a href="{{ route('peminjaman.index') }}"
                   class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 font-medium">
                    Kembali
                </a>
            </div>
        </form>
    </div>

    @if($buku->count() == 0)
        <div class="mt-6 p-6 bg-yellow-50 border border-yellow-300 rounded-lg text-yellow-800">
            Tidak ada buku yang tersedia saat ini.
        </div>
    @endif
</div>
@endsection
