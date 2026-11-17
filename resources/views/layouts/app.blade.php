<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Aplikasi PKL') }}</title>

    <!-- Scripts & Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen pb-20 md:pb-0"> <!-- pb-20 untuk space bottom nav -->
        <!-- Header (Opsional: Bisa disesuaikan) -->
        <header class="bg-white shadow-sm p-4 flex justify-between items-center sticky top-0 z-50">
            <h1 class="font-bold text-xl text-blue-600">PKL App</h1>
            <div class="flex items-center space-x-2">
                <span class="text-sm font-medium">{{ auth()->user()->name ?? '' }}</span>
                <img src="{{ auth()->user()->photo ? asset('storage/'.auth()->user()->photo) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name ?? 'User') }}" class="w-8 h-8 rounded-full object-cover">
            </div>
        </header>

        <!-- Main Content -->
        <main class="p-4">
            @yield('content')
        </main>
    </div>

    <!-- Bottom Navigation (Hanya untuk Mobile) -->
    <x-bottom-nav />

    <script>
        AOS.init({
            duration: 800,
            once: true,
        });
    </script>
</body>
</html>