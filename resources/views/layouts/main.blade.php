<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'PKL Connect')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>

<body>

@php
    $user = auth()->user();
    $role = $user->role;

    $roleLabel = match ($role) {
        'admin' => 'Administrator',
        'pembimbing' => 'Pembimbing',
        default => 'Siswa PKL',
    };
@endphp

<div class="app-wrapper">

    {{-- SIDEBAR --}}
    <aside class="offcanvas-lg offcanvas-start sidebar" tabindex="-1" id="appSidebar">

        <div class="sidebar-brand">
            <div class="brand-icon">
                <i class="bi bi-calendar-check-fill"></i>
            </div>

            <div>
                <h5>PKL Connect</h5>
                <small>Attendance Management</small>
            </div>

            <button type="button"
                    class="btn-close btn-close-white d-lg-none ms-auto"
                    data-bs-dismiss="offcanvas"
                    data-bs-target="#appSidebar">
            </button>
        </div>

        <div class="sidebar-user">
            <div class="user-avatar">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>

            <div class="user-info">
                <strong>{{ $user->name }}</strong>
                <small>{{ $roleLabel }}</small>
            </div>
        </div>

        <nav class="sidebar-menu">

            <div class="menu-label">MENU UTAMA</div>

            @if($role === 'admin')

                <a href="{{ route('admin.dashboard') }}"
                   class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>

                <div class="menu-label">MASTER DATA</div>

                <a href="{{ route('admin.users.index') }}"
                   class="menu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i>
                    <span>Data Pengguna</span>
                </a>

                <a href="{{ route('admin.locations.index') }}"
                   class="menu-link {{ request()->routeIs('admin.locations.*') ? 'active' : '' }}">
                    <i class="bi bi-geo-alt-fill"></i>
                    <span>Lokasi PKL</span>
                </a>

                <a href="{{ route('admin.placements.index') }}"
                   class="menu-link {{ request()->routeIs('admin.placements.*') ? 'active' : '' }}">
                    <i class="bi bi-building-fill"></i>
                    <span>Penempatan PKL</span>
                </a>

                <div class="menu-label">LAPORAN</div>

                <a href="{{ route('admin.rekap') }}"
                   class="menu-link {{ request()->routeIs('admin.rekap*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard-data-fill"></i>
                    <span>Rekap Absensi</span>
                </a>

                <a href="{{ route('admin.jurnal') }}"
                   class="menu-link {{ request()->routeIs('admin.jurnal') ? 'active' : '' }}">
                    <i class="bi bi-journal-text"></i>
                    <span>Rekap Jurnal</span>
                </a>

            @elseif($role === 'pembimbing')

                <a href="{{ route('pembimbing.dashboard') }}"
                   class="menu-link {{ request()->routeIs('pembimbing.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('pembimbing.siswa.index') }}"
                   class="menu-link {{ request()->routeIs('pembimbing.siswa.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i>
                    <span>Siswa Bimbingan</span>
                </a>

            @else

                <a href="{{ route('student.dashboard') }}"
                   class="menu-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>

                <div class="menu-label">AKTIVITAS PKL</div>

                <a href="{{ route('student.absen') }}"
                   class="menu-link {{ request()->routeIs('student.absen*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check-fill"></i>
                    <span>Absensi</span>
                </a>

                <a href="{{ route('student.riwayat') }}"
                   class="menu-link {{ request()->routeIs('student.riwayat') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i>
                    <span>Riwayat Absensi</span>
                </a>

                <a href="{{ route('student.izin.create') }}"
                   class="menu-link {{ request()->routeIs('student.izin.*') ? 'active' : '' }}">
                    <i class="bi bi-envelope-paper-fill"></i>
                    <span>Izin / Sakit</span>
                </a>

                <a href="{{ route('student.jurnal.index') }}"
                   class="menu-link {{ request()->routeIs('student.jurnal.*') ? 'active' : '' }}">
                    <i class="bi bi-journal-text"></i>
                    <span>Jurnal Harian</span>
                </a>

                <a href="{{ route('student.nilai') }}"
                   class="menu-link {{ request()->routeIs('student.nilai') ? 'active' : '' }}">
                    <i class="bi bi-award-fill"></i>
                    <span>Nilai PKL</span>
                </a>

            @endif

            <div class="menu-label">AKUN</div>

            <a href="{{ route('profile.edit') }}"
               class="menu-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="bi bi-person-circle"></i>
                <span>Profil Saya</span>
            </a>

        </nav>

        <div class="sidebar-bottom">
            <div class="sidebar-bottom-info">
                <i class="bi bi-shield-check"></i>
                <span>Sistem Absensi PKL</span>
            </div>
        </div>

    </aside>

    {{-- AREA KONTEN --}}
    <div class="main-wrapper">

        <header class="topbar">

            <div class="d-flex align-items-center gap-3">

                <button class="btn sidebar-toggle d-lg-none"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#appSidebar">
                    <i class="bi bi-list"></i>
                </button>

                <div>
                    <div class="topbar-title">@yield('page-heading', 'Dashboard')</div>
                    <div class="topbar-subtitle">Sistem Informasi Praktik Kerja Lapangan</div>
                </div>

            </div>

            <div class="topbar-right">

                <div class="topbar-date d-none d-md-flex">
                    <i class="bi bi-calendar3"></i>
                    {{ now()->translatedFormat('d F Y') }}
                </div>

                <div class="topbar-divider"></div>

                <div class="topbar-profile">
                    <div class="profile-avatar">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>

                    <div class="profile-text d-none d-sm-block">
                        <strong>{{ $user->name }}</strong>
                        <small>{{ $roleLabel }}</small>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-logout" title="Logout">
                        <i class="bi bi-box-arrow-right"></i>
                        <span class="d-none d-md-inline">Keluar</span>
                    </button>
                </form>

            </div>

        </header>

        <main class="page-content">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')

        </main>

        <footer class="app-footer">
            <span>© {{ date('Y') }} PKL Connect</span>
            <span>Sistem Informasi Praktik Kerja Lapangan</span>
        </footer>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')

</body>
</html>