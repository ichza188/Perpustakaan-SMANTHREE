<nav class="bg-teal-600 text-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <!-- Kiri: Logo + Nama -->
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo-sman3metro.png') }}" alt="Logo" class="h-10 w-10 rounded-full">
                <span class="font-bold text-lg hidden md:block">
                    Perpustakaan SMA 3
                </span>
            </div>

            <!-- Kanan: User + Logout -->
            <div class="flex items-center space-x-4">
                <span class="hidden md:block text-sm">Hi,
                    {{-- {{ auth()->user()->name }} --}}
                </span>
                <form method="POST" action="
                {{ route('logout') }}
                 " class="inline">
                    @csrf
                    <button type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium transition">
                        <i class="fas fa-sign-out-alt mr-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
