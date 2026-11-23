<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PKL Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md">

        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-blue-600">PKL Connect</h1>
            <p class="text-gray-500 text-sm mt-1">
                Silakan login untuk melanjutkan
            </p>
        </div>

        {{-- PESAN ERROR --}}
        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4 text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- FORM LOGIN --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Pengguna
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Masukkan nama pengguna"
                    required
                    autofocus
                    autocomplete="username"
                    class="w-full rounded-lg border border-gray-300 p-3 focus:border-blue-500 focus:ring-blue-500"
                >
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    placeholder="Masukkan password"
                    required
                    autocomplete="current-password"
                    class="w-full rounded-lg border border-gray-300 p-3 focus:border-blue-500 focus:ring-blue-500"
                >
            </div>

            <button
                type="submit"
                class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition"
            >
                Masuk
            </button>

        </form>

        <p class="text-center text-xs text-gray-400 mt-6">
            PKL Connect &copy; {{ date('Y') }}
        </p>

    </div>

</body>
</html>