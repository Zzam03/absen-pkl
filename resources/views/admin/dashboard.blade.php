
@extends('layouts.main')

@section('content')

<div class="container-fluid px-2 px-md-3">

    {{-- Judul Dashboard --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Dashboard Admin</h3>
            <p class="text-muted mb-0">
                Selamat datang! Berikut ringkasan aktivitas PKL.
            </p>
        </div>

        <div class="text-muted small mt-2 mt-md-0">
            <i class="bi bi-calendar3 me-1"></i>
            {{ now()->translatedFormat('l, d F Y') }}
        </div>
    </div>

    {{-- Kartu Statistik Utama --}}
    <div class="row g-3 mb-4">

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="rounded-3 p-3 bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-people-fill fs-4"></i>
                        </div>
                        <span class="badge bg-primary bg-opacity-10 text-primary">
                            Siswa
                        </span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ number_format($totalSiswa) }}</h3>
                    <p class="text-muted mb-0 small">Total Siswa Aktif</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="rounded-3 p-3 bg-success bg-opacity-10 text-success">
                            <i class="bi bi-person-badge-fill fs-4"></i>
                        </div>
                        <span class="badge bg-success bg-opacity-10 text-success">
                            Pembimbing
                        </span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ number_format($totalPembimbing) }}</h3>
                    <p class="text-muted mb-0 small">Total Pembimbing Aktif</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="rounded-3 p-3 bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-building-fill-check fs-4"></i>
                        </div>
                        <span class="badge bg-warning bg-opacity-10 text-dark">
                            Penempatan
                        </span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ number_format($penempatanAktif) }}</h3>
                    <p class="text-muted mb-0 small">Penempatan PKL Aktif</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="rounded-3 p-3 bg-info bg-opacity-10 text-info">
                            <i class="bi bi-calendar-check-fill fs-4"></i>
                        </div>
                        <span class="badge bg-info bg-opacity-10 text-info">
                            Hari ini
                        </span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ number_format($hadirHariIni) }}</h3>
                    <p class="text-muted mb-0 small">Siswa Hadir Hari Ini</p>
                </div>
            </div>
        </div>

    </div>

    {{-- Ringkasan Aktivitas --}}
    <div class="row g-3 mb-4">

        <div class="col-12">
            <h5 class="fw-bold mb-1">Ringkasan Kehadiran</h5>
            <p class="text-muted small mb-0">
                Informasi absensi dan jurnal pada hari ini.
            </p>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-danger bg-opacity-10 text-danger p-3">
                            <i class="bi bi-clock-history fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-1">Belum Absen</p>
                            <h4 class="fw-bold mb-0">
                                {{ number_format($belumAbsen) }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-warning bg-opacity-10 text-warning p-3">
                            <i class="bi bi-geo-alt-fill fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-1">Di Luar Area</p>
                            <h4 class="fw-bold mb-0">
                                {{ number_format($diLuarArea) }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-3">
                            <i class="bi bi-journal-text fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-1">Jurnal Menunggu</p>
                            <h4 class="fw-bold mb-0">
                                {{ number_format($jurnalPending) }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Informasi Dashboard --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-start gap-3">
                <div class="rounded-3 bg-primary bg-opacity-10 text-primary p-3">
                    <i class="bi bi-info-circle-fill fs-4"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Informasi Sistem</h6>
                    <p class="text-muted small mb-0">
                        Dashboard PKL Connect menampilkan ringkasan data
                        siswa, pembimbing, penempatan, kehadiran, dan jurnal.
                        Gunakan menu di sebelah kiri untuk mengelola data
                        dan melihat laporan lebih lanjut.
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
