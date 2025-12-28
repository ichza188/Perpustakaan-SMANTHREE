@props(['user'])

<nav class="bg-teal-600 text-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center space-x-8">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <img src="{{ asset('images/logo-sman3metro.png') }}" alt="Logo" class="h-10 w-10">
                    <span class="font-bold text-lg">Perpustakaan SMA N 3 Metro</span>
                </a>

                <!-- Menu Utama -->
                <div class="hidden md:flex space-x-6">
                    <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                        Beranda
                    </x-nav-link>
                    <x-nav-link href="{{ route('katalog') }}" :active="request()->routeIs('katalog')">
                        Katalog Buku
                    </x-nav-link>
                    <x-nav-link href="{{ route('tentang') }}">
                        Tentang
                    </x-nav-link>
                </div>
            </div>

            <!-- Right Menu -->
            <div class="flex items-center space-x-4">
                @auth
                    <span class="hidden md:block">Hi, {{ auth()->user()->name }}</span>
                    <x-dropdown>
                        <x-slot name="trigger">
                            <button class="flex items-center space-x-1">
                                <i class="fas fa-user-circle text-xl"></i>
                                <i class="fas fa-caret-down"></i>
                            </button>
                        </x-slot>

                        <x-dropdown-item href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                        </x-dropdown-item>
                        <x-dropdown-item href="{{ route('logout') }}" method="post">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </x-dropdown-item>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="bg-white text-teal-600 px-4 py-2 rounded-md font-medium hover:bg-gray-100">
                        Login Admin
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
