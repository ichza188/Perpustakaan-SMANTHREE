<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin')</title>
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Tailwind CDN (TANPA DARK MODE) -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
    <script>
      tailwind.config = {
        darkMode: 'class', // Paksa light mode
        corePlugins: { preflight: true },
        theme: { extend: {} },
        plugins: [],
      }
    </script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    @include('layouts.partials.datatables-style')
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen font-sans">

    <!-- Navbar -->
    <x-admin.navbar />

    <x-alert />
    <div class="flex">
        <!-- Sidebar -->
        <x-admin.sidebar />

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                @yield('content')
            </div>
        </main>
    </div>

    @include('layouts.partials.datatables-script')
    @stack('scripts')
</body>
</html>
