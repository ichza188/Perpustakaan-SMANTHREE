<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Siswa')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans">

    <!-- Navbar Atas -->
    <x-siswa.navbar />

    <x-alert />
    <div class="flex">
        <!-- Sidebar Kiri -->
        <x-siswa.sidebar />

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
