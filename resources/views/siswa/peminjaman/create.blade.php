{{-- resources/views/siswa/peminjaman/create.blade.php --}}
@extends('layouts.siswa')

@section('title', 'Pinjam Buku Baru')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-800">Pinjam Buku Baru</h1>
        <p class="text-gray-600 mt-2">Cari buku yang ingin dipinjam, lalu klik tombol "Ajukan"</p>
    </div>

    <!-- Search Box -->
    <div class="mb-8">
        <div class="relative max-w-2xl mx-auto">
            <input type="text" id="searchInput"
                   placeholder="Ketik judul buku atau pengarang..."
                   class="w-full px-6 py-4 text-lg border-2 border-gray-300 rounded-xl focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-100 transition-all"
                   autocomplete="off">
            <div class="absolute right-4 top-5 text-gray-400">
                <i class="fas fa-search text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Daftar Buku -->
    <div id="bukuContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($buku as $b)
            <div class="buku-card bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border-2 border-gray-100"
                 data-judul="{{ strtolower($b->judul) }}"
                 data-pengarang="{{ strtolower($b->pengarang) }}">
                <div class="bg-gradient-to-br from-teal-500 to-blue-600 p-8 text-white text-center">
                    <i class="fas fa-book-open text-5xl opacity-20"></i>
                </div>
                <div class="p-6">
                    <h3 class="font-bold text-lg text-gray-800 mb-2 line-clamp-2">{{ $b->judul }}</h3>
                    <p class="text-sm text-gray-600 mb-1">
                        <i class="fas fa-user-edit mr-2"></i>{{ $b->pengarang }}
                    </p>
                    <p class="text-sm text-gray-500 mb-4">
                        <i class="fas fa-barcode mr-2"></i>{{ $b->kode_buku }}
                    </p>

                    <!-- Stok Indicator -->
                    <div class="mb-4">
                        @if($b->stokTersedia() > 0)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Tersedia {{ $b->stokTersedia() }} buah
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Habis
                            </span>
                        @endif
                    </div>

                    <!-- Tombol Ajukan -->
                    @if($b->stokTersedia() > 0)
                        <form action="{{ route('siswa.peminjaman.store') }}" method="POST" class="mt-4">
                            @csrf
                            <input type="hidden" name="buku_id" value="{{ $b->id }}">
                            <button type="submit"
                                    class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-6 rounded-xl transition-all transform hover:scale-105 active:scale-95 shadow-lg">
                                Ajukan Peminjaman
                            </button>
                        </form>
                    @else
                        <button disabled class="w-full bg-gray-400 text-white font-bold py-3 px-6 rounded-xl cursor-not-allowed opacity-60">
                            Stok Habis
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-16">
                <i class="fas fa-book-reader text-6xl text-gray-300 mb-4"></i>
                <p class="text-xl text-gray-500">Belum ada buku yang tersedia</p>
            </div>
        @endforelse
    </div>

    <!-- Loading -->
    <div id="loading" class="hidden text-center py-8">
        <i class="fas fa-spinner fa-spin text-4xl text-teal-600"></i>
        <p class="mt-4 text-gray-600">Mencari buku...</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('searchInput').addEventListener('input', function(e) {
    const query = e.target.value.toLowerCase();
    const cards = document.querySelectorAll('.buku-card');
    const container = document.getElementById('bukuContainer');
    const loading = document.getElementById('loading');

    let visible = 0;

    if (query === '') {
        cards.forEach(card => {
            card.style.display = 'block';
            visible++;
        });
        loading.classList.add('hidden');
        container.classList.remove('hidden');
        return;
    }

    loading.classList.remove('hidden');
    container.classList.add('hidden');

    setTimeout(() => {
        cards.forEach(card => {
            const judul = card.dataset.judul;
            const pengarang = card.dataset.pengarang;

            if (judul.includes(query) || pengarang.includes(query)) {
                card.style.display = 'block';
                visible++;
            } else {
                card.style.display = 'none';
            }
        });

        loading.classList.add('hidden');
        container.classList.remove('hidden');

        if (visible === 0) {
            container.innerHTML = `
                <div class="col-span-full text-center py-16">
                    <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                    <p class="text-xl text-gray-500">Buku tidak ditemukan</p>
                </div>
            `;
        }
    }, 300);
});
</script>
@endpush
