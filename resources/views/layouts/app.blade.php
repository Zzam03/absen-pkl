<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Aplikasi PKL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body class="bg-gray-50 font-sans antialiased pb-20">
    <header class="bg-white shadow-sm p-4 flex justify-between items-center sticky top-0 z-50">
        <h1 class="font-bold text-xl text-blue-600">PKL App</h1>
      <a href="{{ route('profile.edit') }}" class="flex items-center space-x-2">
            <span class="text-sm font-medium hidden md:block">{{ auth()->user()->name ?? '' }}</span>
            <img src="{{ auth()->user()->photo ? asset('storage/'.auth()->user()->photo) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name ?? 'User') }}" class="w-8 h-8 rounded-full object-cover">
        </a>
    </header>

    <main class="p-4 max-w-7xl mx-auto">
        @yield('content')
    </main>

    <div class="fixed bottom-0 w-full bg-white border-t border-gray-200 flex justify-around p-3 md:hidden z-50">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-400' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span class="text-[10px] mt-1">Home</span>
        </a>
        @if(auth()->user()->role === 'student')
        <a href="/siswa/absen" class="flex flex-col items-center {{ request()->is('siswa/absen') ? 'text-blue-600' : 'text-gray-400' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
            <span class="text-[10px] mt-1">Absen</span>
        </a>
        @endif
        <a href="{{ route('profile.edit') }}" class="flex flex-col items-center {{ request()->routeIs('profile.*') ? 'text-blue-600' : 'text-gray-400' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            <span class="text-[10px] mt-1">Profil</span>
        </a>
    </div>
    <script>AOS.init({ duration: 800, once: true });</script>
</body>
</html>