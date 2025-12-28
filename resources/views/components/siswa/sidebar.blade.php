{{-- resources/views/components/siswa/sidebar.blade.php --}}
<aside class="w-72 bg-gradient-to-b from-indigo-700 via-indigo-800 to-indigo-900 text-white min-h-screen shadow-2xl relative">
    <div class="p-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="w-24 h-24 mx-auto mb-5 bg-white rounded-full shadow-2xl overflow-hidden border-6 border-yellow-400 p-2">
                <img src="{{ asset('images/logo-sman3metro.png') }}" alt="Logo" class="w-full h-full object-contain rounded-full">
            </div>
            <h1 class="text-3xl font-bold text-yellow-300 tracking-wider drop-shadow-md">SISWA</h1>
            <p class="text-indigo-200 text-sm mt-1 font-medium">SMA Negeri 3 Metro</p>
        </div>

        <nav class="space-y-4">
            <!-- Dashboard -->
            <x-siswa.sidebar-link href="{{ route('siswa.dashboard') }}" :active="request()->routeIs('siswa.dashboard')">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </x-siswa.sidebar-link>

            <!-- Peminjaman -->
            <x-siswa.sidebar-link href="{{ route('siswa.peminjaman.index') }}" :active="request()->routeIs('siswa.peminjaman.*')">
                <i class="fas fa-book-reader"></i>
                <span>Peminjaman Saya</span>
            </x-siswa.sidebar-link>

            <!-- Pengembalian -->
            <x-siswa.sidebar-link href="{{ route('siswa.pengembalian.index') }}" :active="request()->routeIs('siswa.pengembalian.*')">
                <i class="fas fa-undo-alt"></i>
                <span>Pengembalian Buku</span>
            </x-siswa.sidebar-link>

            <!-- Bebas Perpus -->
            <x-siswa.sidebar-link href="{{ route('bebas-perpus.index') }}" :active="request()->routeIs('siswa.bebas-perpus')">
                <i class="fas fa-graduation-cap"></i>
                <span>Bebas Perpustakaan</span>
            </x-siswa.sidebar-link>
        </nav>
    </div>

    <!-- Profil Siswa & Logout -->
    <div class="absolute bottom-0 w-full p-6 bg-indigo-950/80 border-t-2 border-indigo-700">
        <div class="flex items-center mb-5">
            <div class="w-14 h-14 bg-yellow-400 rounded-full flex items-center justify-center text-indigo-900 font-bold text-2xl shadow-2xl">
                {{ substr(auth()->user()->siswa->nama, 0, 2) }}
            </div>
            <div class="ml-4">
                <p class="font-bold text-yellow-300 text-lg">{{ auth()->user()->siswa->nama }}</p>
                <p class="text-indigo-200 text-xs">{{ auth()->user()->siswa->kelas }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left flex items-center text-indigo-200 hover:text-white transition-all duration-200 text-lg font-medium">
                <i class="fas fa-sign-out-alt mr-3"></i> Logout
            </button>
        </form>
    </div>
</aside>
