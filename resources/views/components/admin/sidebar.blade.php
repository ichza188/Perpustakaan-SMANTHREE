{{-- resources/views/components/admin/sidebar.blade.php --}}
<aside class="w-72 bg-gradient-to-b from-teal-800 via-teal-900 to-teal-950 text-white min-h-screen shadow-2xl relative overflow-y-auto">
    <div class="p-8">
        <!-- Logo & Header -->
        <div class="text-center mb-12">
            <div class="w-24 h-24 mx-auto mb-5 bg-white rounded-full shadow-2xl overflow-hidden border-6 border-yellow-400 p-2">
                <img src="{{ asset('images/logo-sman3metro.png') }}" alt="Logo SMA N 3 Metro" class="w-full h-full object-contain rounded-full">
            </div>
            <h1 class="text-3xl font-bold text-yellow-300 tracking-wider drop-shadow-md">ADMIN</h1>
            <p class="text-teal-200 text-sm mt-1 font-medium">SMA Negeri 3 Metro</p>
        </div>

        <nav class="space-y-4">
            <!-- Dashboard -->
            <x-admin.sidebar-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                <i class="fas fa-tachometer-alt m-1"></i>
                <span>Dashboard</span>
            </x-admin.sidebar-link>

            <!-- Kelola Siswa -->
            <x-admin.sidebar-link href="{{ route('admin.siswa.index') }}" :active="request()->routeIs('admin.siswa.*')">
                <i class="fas fa-users m-1"></i>
                <span>Kelola Siswa</span>
            </x-admin.sidebar-link>

            <!-- Kelola Buku -->
            <x-admin.sidebar-link href="{{ route('buku.index') }}" :active="request()->routeIs('buku.*')">
                <i class="fas fa-book m-1"></i>
                <span>Kelola Buku</span>
            </x-admin.sidebar-link>

            <!-- Validasi Peminjaman -->
            <x-admin.sidebar-link href="{{ route('admin.peminjaman.index') }}" :active="request()->routeIs('admin.peminjaman.*')">
                <i class="fas fa-clipboard-check m-1"></i>
                <span>Validasi Peminjaman</span>
                @if($pending = \App\Models\Peminjaman::where('status', 'pending')->count())
                    <span class="ml-auto bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full animate-pulse shadow-lg">
                        {{ $pending }}
                    </span>
                @endif
            </x-admin.sidebar-link>

            <!-- Pengembalian Buku -->
            <x-admin.sidebar-link href="{{ route('admin.peminjaman.index') }}?status=pengajuan_kembali"
                :active="request()->get('status') === 'pengajuan_kembali'">
                <i class="fas fa-undo-alt m-1"></i>
                <span>Pengembalian Buku</span>
                @if($kembali = \App\Models\Peminjaman::where('status', 'pengajuan_kembali')->count())
                    <span class="ml-auto bg-orange-600 text-white text-xs font-bold px-3 py-1 rounded-full animate-pulse shadow-lg">
                        {{ $kembali }}
                    </span>
                @endif
            </x-admin.sidebar-link>

            <!-- Bebas Perpus Admin -->
            <x-admin.sidebar-link href="{{ route('admin.bebas-perpus.index') }}" :active="request()->routeIs('admin.bebas-perpus.*')">
                <i class="fas fa-graduation-cap m-1"></i>
                <span>Bebas Perpustakaan</span>
                @if($bebas = \App\Models\Siswa::where('status_bebas_perpus', 'diajukan')->count())
                    <span class="ml-auto bg-purple-600 text-white text-xs font-bold px-3 py-1 rounded-full animate-pulse">
                        {{ $bebas }}
                    </span>
                @endif
            </x-admin.sidebar-link>
        </nav>
    </div>

    <!-- Profil & Logout -->
    <div class="absolute bottom-0 w-full p-6 bg-teal-950/80 border-t-2 border-teal-700">
        <div class="flex items-center mb-5">
            <div class="w-14 h-14 bg-yellow-400 rounded-full flex items-center justify-center text-teal-900 font-bold text-2xl shadow-2xl">
                {{ substr(auth()->user()->username, 0, 2) }}
            </div>
            <div class="ml-4">
                <p class="font-bold text-yellow-300 text-lg">{{ auth()->user()->username }}</p>
                <p class="text-teal-200 text-xs">Administrator</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left flex items-center text-teal-200 hover:text-white transition-all duration-200 text-lg font-medium">
                <i class="fas fa-sign-out-alt mr-3 m-1"></i> Logout
            </button>
        </form>
    </div>
</aside>
