
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'PKL Connect')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @stack('styles')

    <script>
        tailwind.config = {
            corePlugins: {
                preflight: false
            }
        };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .mobile-bottom-nav {
            display: none;
        }

        @media (max-width: 991.98px) {
            .mobile-bottom-nav {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                z-index: 1040;
                display: grid;
                grid-template-columns: repeat(5, 1fr);
                align-items: center;
                min-height: 70px;
                padding: 7px 8px calc(7px + env(safe-area-inset-bottom));
                background: #ffffff;
                border-top: 1px solid #e5e7eb;
                box-shadow: 0 -4px 18px rgba(15, 23, 42, 0.08);
            }

            .mobile-nav-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 4px;
                min-width: 0;
                color: #64748b;
                text-decoration: none;
                background: transparent;
                border: none;
                font-size: 10px;
                font-weight: 600;
                line-height: 1.2;
                padding: 5px 2px;
                white-space: nowrap;
            }

            .mobile-nav-item i {
                font-size: 20px;
                line-height: 1;
            }

            .mobile-nav-item.active {
                color: #2563eb;
            }

            .mobile-nav-item:active {
                color: #1d4ed8;
            }

            .mobile-nav-center {
                position: relative;
                margin-top: -24px;
            }

            .mobile-nav-center .nav-center-icon {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 53px;
                height: 53px;
                margin-bottom: 2px;
                border-radius: 18px;
                background: #2563eb;
                color: #ffffff;
                box-shadow: 0 5px 12px rgba(37, 99, 235, 0.3);
                font-size: 23px;
            }

            .mobile-nav-center.active .nav-center-icon {
                background: #1d4ed8;
            }

            .mobile-nav-center span {
                color: #2563eb;
            }

            .main-wrapper {
                padding-bottom: 85px;
            }

            .app-footer {
                margin-bottom: 5px;
            }
        }

        @media (max-width: 380px) {
            .mobile-nav-item {
                font-size: 9px;
            }

            .mobile-nav-item i {
                font-size: 18px;
            }

            .mobile-nav-center .nav-center-icon {
                width: 48px;
                height: 48px;
            }
        }

        /* POPUP MENU KHUSUS NAVBAR SISWA */
        @media (max-width: 991.98px) {
            .mobile-menu-wrap {
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
                min-width: 0;
            }

            .mobile-menu-popover {
                display: none;
                position: absolute;
                right: 0;
                bottom: calc(100% + 12px);
                width: 190px;
                max-width: calc(100vw - 24px);
                padding: 7px;
                background: #ffffff;
                border: 1px solid #e5e7eb;
                border-radius: 15px;
                box-shadow: 0 8px 28px rgba(15, 23, 42, 0.18);
                z-index: 1060;
            }

            .mobile-menu-popover.show {
                display: block;
                animation: mobileMenuAppear 0.16s ease-out;
            }

            .mobile-menu-link {
                display: flex;
                align-items: center;
                gap: 11px;
                padding: 12px 11px;
                color: #334155;
                text-decoration: none;
                font-size: 12px;
                font-weight: 600;
                border-radius: 10px;
            }

            .mobile-menu-link i {
                font-size: 17px;
                color: #2563eb;
            }

            .mobile-menu-link.active,
            .mobile-menu-link:active {
                color: #2563eb;
                background: #eff6ff;
            }

            @keyframes mobileMenuAppear {
                from {
                    opacity: 0;
                    transform: translateY(6px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        }
    </style>
</head>

<body>

@php
    $user = auth()->user();
    $role = $user->role;

    $roleLabel = match ($role) {
        'admin' => 'Administrator',
        'pembimbing_kantor' => 'Pembimbing Kantor',
        'pembimbing_sekolah' => 'Pembimbing Sekolah',
        'student' => 'Siswa PKL',
        default => 'Pengguna',
    };
@endphp

<div class="app-wrapper">

    {{-- SIDEBAR --}}
    <aside class="offcanvas-lg offcanvas-start sidebar" tabindex="-1" id="appSidebar">

        <div class="sidebar-brand">
            <img
                src="{{ asset('images/logo-msn2.png') }}"
                alt="Logo MSN"
                class="brand-logo"
            >

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

                <a href="{{ route('dashboard') }}"
                   class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
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

                <a href="{{ route('admin.schedule.index') }}"
                   class="menu-link {{ request()->routeIs('admin.schedule.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-week-fill"></i>
                    <span>Jadwal PKL</span>
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

            @elseif(in_array($role, ['pembimbing_kantor', 'pembimbing_sekolah']))

                <a href="{{ route('dashboard') }}"
                   class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('pembimbing.siswa.index') }}"
                   class="menu-link {{ request()->routeIs('pembimbing.siswa.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i>
                    <span>Siswa Bimbingan</span>
                </a>

                <a href="{{ route('pembimbing.izin.index') }}"
                   class="menu-link {{ request()->routeIs('pembimbing.izin.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check-fill"></i>
                    <span>Persetujuan Izin / Sakit</span>
                </a>
                {{-- MENU KHUSUS PEMBIMBING KANTOR (AZMI) --}}
                @if($role === 'pembimbing_kantor')

                    <div class="menu-label">MONITORING PKL</div>

                    <a href="{{ route('pembimbing.kantor.absensi') }}"
                       class="menu-link {{ request()->routeIs('pembimbing.kantor.absensi') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i>
                        <span>Riwayat Absensi</span>
                    </a>

                    <a href="{{ route('pembimbing.kantor.jurnal') }}"
                       class="menu-link {{ request()->routeIs('pembimbing.kantor.jurnal') ? 'active' : '' }}">
                        <i class="bi bi-journal-text"></i>
                        <span>Jurnal Harian</span>
                    </a>

                    <a href="{{ route('pembimbing.jadwal.index') }}"
                       class="menu-link {{ request()->routeIs('pembimbing.jadwal.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-week-fill"></i>
                        <span>Jadwal PKL</span>
                    </a>

                @endif

                {{-- MENU KHUSUS PEMBIMBING SEKOLAH (AGISTA) --}}
                @if($role === 'pembimbing_sekolah')

                    <div class="menu-label">MONITORING SEKOLAH</div>

                    <a href="{{ route('pembimbing.sekolah.jadwal.index') }}"
                       class="menu-link {{ request()->routeIs('pembimbing.sekolah.jadwal.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-week-fill"></i>
                        <span>Jadwal Siswa</span>
                    </a>

                    <a href="{{ route('pembimbing.rekap.absensi') }}"
                       class="menu-link {{ request()->routeIs('pembimbing.rekap.absensi') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check-fill"></i>
                        <span>Rekap Absensi</span>
                    </a>

                    <a href="{{ route('pembimbing.rekap.jurnal') }}"
                       class="menu-link {{ request()->routeIs('pembimbing.rekap.jurnal') ? 'active' : '' }}">
                        <i class="bi bi-journal-text"></i>
                        <span>Rekap Jurnal</span>
                    </a>

                @endif

            @else

                <a href="{{ route('dashboard') }}"
                   class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
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

{{-- NAVBAR BAWAH KHUSUS HP --}}
<nav class="mobile-bottom-nav d-lg-none">

    @if($role === 'admin')

        <a href="{{ route('admin.users.index') }}"
           class="mobile-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i>
            <span>Pengguna</span>
        </a>

        <a href="{{ route('admin.locations.index') }}"
           class="mobile-nav-item {{ request()->routeIs('admin.locations.*') ? 'active' : '' }}">
            <i class="bi bi-geo-alt-fill"></i>
            <span>Lokasi</span>
        </a>

        <a href="{{ route('dashboard') }}"
           class="mobile-nav-item mobile-nav-center {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="nav-center-icon">
                <i class="bi bi-house-door-fill"></i>
            </span>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.placements.index') }}"
           class="mobile-nav-item {{ request()->routeIs('admin.placements.*') ? 'active' : '' }}">
            <i class="bi bi-building-fill"></i>
            <span>Penempatan</span>
        </a>

        <div class="mobile-menu-wrap">
            <button type="button" class="mobile-nav-item"
                    id="mobileMenuTrigger"
                    aria-expanded="false"
                    aria-controls="mobileMenuPopover">
                <i class="bi bi-grid-fill"></i>
                <span>Menu</span>
            </button>

            <div class="mobile-menu-popover" id="mobileMenuPopover">
                <a href="{{ route('admin.rekap') }}"
                   class="mobile-menu-link {{ request()->routeIs('admin.rekap*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard-data-fill"></i>
                    <span>Rekap Absensi</span>
                </a>

                <a href="{{ route('admin.schedule.index') }}"
                   class="mobile-menu-link {{ request()->routeIs('admin.schedule.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-week-fill"></i>
                    <span>Jadwal</span>
                </a>

                <a href="{{ route('admin.jurnal') }}"
                   class="mobile-menu-link {{ request()->routeIs('admin.jurnal') ? 'active' : '' }}">
                    <i class="bi bi-journal-text"></i>
                    <span>Rekap Jurnal</span>
                </a>

                <a href="{{ route('profile.edit') }}"
                   class="mobile-menu-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="bi bi-person-circle"></i>
                    <span>Profil Saya</span>
                </a>
            </div>
        </div>

    @elseif(in_array($role, ['pembimbing_kantor', 'pembimbing_sekolah']))

        <a href="{{ route('pembimbing.siswa.index') }}"
           class="mobile-nav-item {{ request()->routeIs('pembimbing.siswa.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i>
            <span>Siswa</span>
        </a>

        @if($role === 'pembimbing_sekolah')
            <a href="{{ route('pembimbing.rekap.absensi') }}"
               class="mobile-nav-item {{ request()->routeIs('pembimbing.rekap.absensi') ? 'active' : '' }}">
                <i class="bi bi-calendar-check-fill"></i>
                <span>Absensi</span>
            </a>
        @else
            <a href="{{ route('pembimbing.kantor.absensi') }}"
               class="mobile-nav-item {{ request()->routeIs('pembimbing.kantor.absensi') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i>
                <span>Absensi</span>
            </a>
        @endif

        <a href="{{ route('dashboard') }}"
           class="mobile-nav-item mobile-nav-center {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="nav-center-icon">
                <i class="bi bi-house-door-fill"></i>
            </span>
            <span>Dashboard</span>
        </a>

        @if($role === 'pembimbing_sekolah')
            <a href="{{ route('pembimbing.rekap.jurnal') }}"
               class="mobile-nav-item {{ request()->routeIs('pembimbing.rekap.jurnal') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i>
                <span>Jurnal</span>
            </a>
        @else
            <a href="{{ route('pembimbing.kantor.jurnal') }}"
               class="mobile-nav-item {{ request()->routeIs('pembimbing.kantor.jurnal') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i>
                <span>Jurnal</span>
            </a>
        @endif

        <div class="mobile-menu-wrap">
            <button type="button" class="mobile-nav-item"
                    id="mobileMenuTrigger"
                    aria-expanded="false"
                    aria-controls="mobileMenuPopover">
                <i class="bi bi-grid-fill"></i>
                <span>Menu</span>
            </button>

            <div class="mobile-menu-popover" id="mobileMenuPopover">

                <a href="{{ route('pembimbing.izin.index') }}"
                   class="mobile-menu-link {{ request()->routeIs('pembimbing.izin.*') ? 'active' : '' }}">
                    <i class="bi bi-envelope-paper-fill"></i>
                    <span>Pengajuan Izin</span>
                </a>

                @if($role === 'pembimbing_sekolah')
                    <a href="{{ route('pembimbing.sekolah.jadwal.index') }}"
                       class="mobile-menu-link {{ request()->routeIs('pembimbing.sekolah.jadwal.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-week-fill"></i>
                        <span>Jadwal Sekolah</span>
                    </a>
                @else
                    <a href="{{ route('pembimbing.jadwal.index') }}"
                       class="mobile-menu-link {{ request()->routeIs('pembimbing.jadwal.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-week-fill"></i>
                        <span>Jadwal</span>
                    </a>
                @endif

                <a href="{{ route('profile.edit') }}"
                   class="mobile-menu-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="bi bi-person-circle"></i>
                    <span>Profil Saya</span>
                </a>
            </div>
        </div>

    @else

        <a href="{{ route('student.absen') }}"
           class="mobile-nav-item {{ request()->routeIs('student.absen*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check-fill"></i>
            <span>Absensi</span>
        </a>

        <a href="{{ route('student.riwayat') }}"
           class="mobile-nav-item {{ request()->routeIs('student.riwayat') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i>
            <span>Riwayat</span>
        </a>

        <a href="{{ route('dashboard') }}"
           class="mobile-nav-item mobile-nav-center {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="nav-center-icon">
                <i class="bi bi-house-door-fill"></i>
            </span>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('student.jurnal.index') }}"
           class="mobile-nav-item {{ request()->routeIs('student.jurnal.*') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i>
            <span>Jurnal</span>
        </a>

        <div class="mobile-menu-wrap">
            <button type="button" class="mobile-nav-item"
                    id="mobileMenuTrigger"
                    aria-expanded="false"
                    aria-controls="mobileMenuPopover">
                <i class="bi bi-grid-fill"></i>
                <span>Menu</span>
            </button>

            <div class="mobile-menu-popover" id="mobileMenuPopover">
                <a href="{{ route('student.izin.create') }}"
                   class="mobile-menu-link {{ request()->routeIs('student.izin.*') ? 'active' : '' }}">
                    <i class="bi bi-envelope-paper-fill"></i>
                    <span>Izin / Sakit</span>
                </a>

                <a href="{{ route('student.nilai') }}"
                   class="mobile-menu-link {{ request()->routeIs('student.nilai') ? 'active' : '' }}">
                    <i class="bi bi-award-fill"></i>
                    <span>Nilai PKL</span>
                </a>

                <a href="{{ route('profile.edit') }}"
                   class="mobile-menu-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="bi bi-person-circle"></i>
                    <span>Profil Saya</span>
                </a>
            </div>
        </div>

    @endif

</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<style>
    /* SWEETALERT2 - DESAIN COMPACT PKL CONNECT */
    .swal2-popup {
        width: min(380px, calc(100vw - 32px)) !important;
        padding: 26px 24px 22px !important;
        border-radius: 20px !important;
        font-family: 'Inter', sans-serif !important;
    }



    .swal2-title {
        padding: 0 !important;
        margin: 0 0 9px !important;
        font-size: 21px !important;
        font-weight: 700 !important;
        color: #14213d !important;
    }

    .swal2-html-container {
        margin: 0 !important;
        font-size: 13.5px !important;
        line-height: 1.6 !important;
        color: #64748b !important;
    }

    .swal2-actions {
        margin: 19px auto 0 !important;
    }

    .swal2-confirm {
        min-width: 90px !important;
        padding: 10px 23px !important;
        border-radius: 9px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        box-shadow: none !important;
    }

    @media (max-width: 480px) {
        .swal2-popup {
            width: calc(100vw - 36px) !important;
            padding: 23px 20px 20px !important;
        }

        .swal2-title {
            font-size: 19px !important;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    @if(session()->has('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: @json(session('success')),
            confirmButtonText: 'Oke',
            confirmButtonColor: '#2563eb',
            customClass: {
                popup: 'rounded-4',
                confirmButton: 'rounded-3 px-4'
            }
        });
    @endif

    @if(session()->has('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: @json(session('error')),
            confirmButtonText: 'Mengerti',
            confirmButtonColor: '#2563eb',
            customClass: {
                popup: 'rounded-4',
                confirmButton: 'rounded-3 px-4'
            }
        });
    @endif
</script>

@stack('scripts')


<script>
document.addEventListener('DOMContentLoaded', function () {
    const trigger = document.getElementById('mobileMenuTrigger');
    const popup = document.getElementById('mobileMenuPopover');

    if (!trigger || !popup) return;

    function closeMobileMenu() {
        popup.classList.remove('show');
        trigger.setAttribute('aria-expanded', 'false');
    }

    trigger.addEventListener('click', function (event) {
        event.stopPropagation();

        const isOpen = popup.classList.toggle('show');
        trigger.setAttribute('aria-expanded', String(isOpen));
    });

    popup.addEventListener('click', function (event) {
        event.stopPropagation();

        if (event.target.closest('a')) {
            closeMobileMenu();
        }
    });

    document.addEventListener('click', closeMobileMenu);

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeMobileMenu();
        }
    });
});
</script>
</body>
</html>
