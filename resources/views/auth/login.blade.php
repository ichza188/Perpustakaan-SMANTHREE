@extends('layouts.guest')

@section('title', 'Login Admin - Perpustakaan')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-cover bg-center"
     style="background-image: url('{{ asset('images/sman3metro.jpg') }}')">

    <div class="bg-white bg-opacity-95 p-8 rounded-xl shadow-2xl w-full max-w-md">
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo-sman3metro.png') }}" alt="Logo" class="h-16 mx-auto mb-4">
            <h1 class="text-2xl font-bold text-teal-700">Login Admin</h1>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">
                    <i class="fas fa-user mr-2"></i> Username
                </label>
                <input type="text" name="username" required autofocus
                       class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent
                              @error('username') border-red-500 @enderror"
                       placeholder="Masukkan username">
                @error('username')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 mb-2">
                    <i class="fas fa-lock mr-2"></i> Password
                </label>
                <input type="password" name="password" required
                       class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent
                              @error('password') border-red-500 @enderror"
                       placeholder="Masukkan password">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full bg-teal-600 text-white py-3 rounded-lg font-semibold hover:bg-teal-700 transition">
                <i class="fas fa-sign-in-alt mr-2"></i> Login
            </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-6">
            &copy; {{ date('Y') }} SMA Negeri 3 Metro
        </p>
    </div>
</div>
@endsection
