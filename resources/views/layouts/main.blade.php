<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Absen PKL')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">Absen PKL</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav me-auto">
                @php $role = auth()->user()->role; @endphp
                @if($role === 'admin')
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.users.index') }}">User</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.locations.index') }}">Lokasi</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.placements.index') }}">Penempatan</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.rekap') }}">Rekap Absen</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.jurnal') }}">Rekap Jurnal</a></li>
                @elseif($role === 'pembimbing')
                    <li class="nav-item"><a class="nav-link" href="{{ route('pembimbing.dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('pembimbing.siswa.index') }}">Siswa Bimbingan</a></li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('student.dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('student.absen') }}">Absen</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('student.riwayat') }}">Riwayat</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('student.jurnal.index') }}">Jurnal</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('student.izin.create') }}">Izin / Sakit</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('student.nilai') }}">Nilai</a></li>
                @endif
            </ul>
            <span class="navbar-text text-white me-3">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-light btn-sm">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>