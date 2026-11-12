
@extends('layouts.main')

@section('title', 'Dashboard Siswa - PKL Connect')
@section('page-heading', 'Dashboard Siswa')

@section('content')

<div class="mb-4">
    <h4 class="fw-bold mb-1">Halo, {{ auth()->user()->name }}! 👋</h4>
    <p class="text-muted mb-0">
        Selamat datang di dashboard PKL Connect. Pantau aktivitas PKL kamu di sini.
    </p>
</div>

{{-- INFORMASI PENEMPATAN --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <span class="badge bg-primary-subtle text-primary mb-2">
                    <i class="bi bi-building me-1"></i> Penempatan PKL
                </span>

                @if($placement)
                    <h5 class="fw-bold mb-1">
                        {{ $placement->location->name ?? 'Lokasi belum tersedia' }}
                    </h5>
                    <p class="text-muted mb-1">
                        Pembimbing: {{ $placement->pembimbing->name ?? '-' }}
                    </p>
                    <small class="text-muted">
                        Periode:
                        {{ $placement->start_date->format('d M Y') }}
                        -
                        {{ $placement->end_date->format('d M Y') }}
                    </small>
                @else
                    <h5 class="fw-bold mb-1">Belum Ada Penempatan</h5>
                    <p class="text-muted mb-0">
                        Silakan hubungi administrator untuk informasi penempatan PKL.
                    </p>
                @endif
            </div>

            <div class="rounded-circle bg-primary-subtle p-3">
                <i class="bi bi-geo-alt-fill text-primary fs-3"></i>
            </div>
        </div>
    </div>
</div>

{{-- STATISTIK --}}
<div class="row g-3 mb-4">

    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-2">Absensi Hari Ini</p>
                        <h4 class="fw-bold mb-0">
                            {{ $absenHariIni ? 'Sudah Absen' : 'Belum Absen' }}
                        </h4>
                    </div>
                    <div class="bg-success-subtle rounded-3 p-3">
                        <i class="bi bi-calendar-check text-success fs-4"></i>
                    </div>
                </div>
                <small class="text-muted">
                    @if($absenHariIni)
                        Masuk: {{ $absenHariIni->check_in_time ? substr($absenHariIni->check_in_time, 0, 5) : '-' }}
                        <br>
                        Pulang: {{ $absenHariIni->check_out_time ? substr($absenHariIni->check_out_time, 0, 5) : '-' }}
                    @else
                        Kamu belum melakukan absensi hari ini.
                    @endif
                </small>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-2">Jurnal Menunggu</p>
                        <h2 class="fw-bold mb-0">{{ $jurnalPending }}</h2>
                    </div>
                    <div class="bg-warning-subtle rounded-3 p-3">
                        <i class="bi bi-hourglass-split text-warning fs-4"></i>
                    </div>
                </div>
                <small class="text-muted">Menunggu paraf pembimbing</small>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-2">Jurnal Perlu Revisi</p>
                        <h2 class="fw-bold mb-0">{{ $jurnalRevisi }}</h2>
                    </div>
                    <div class="bg-danger-subtle rounded-3 p-3">
                        <i class="bi bi-exclamation-circle text-danger fs-4"></i>
                    </div>
                </div>
                <small class="text-muted">Periksa dan perbaiki jurnal kamu</small>
            </div>
        </div>
    </div>

</div>

{{-- MENU CEPAT --}}
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-3">Menu Cepat</h5>

        <div class="row g-3">

            <div class="col-md-4">
                <a href="{{ route('student.absen') }}"
                   class="btn btn-outline-primary w-100 p-3 text-start">
                    <i class="bi bi-calendar-check fs-4 d-block mb-2"></i>
                    <strong>Absensi</strong>
                    <small class="d-block text-muted">Catat kehadiran PKL</small>
                </a>
            </div>

            <div class="col-md-4">
                <a href="{{ route('student.jurnal.create') }}"
                   class="btn btn-outline-primary w-100 p-3 text-start">
                    <i class="bi bi-journal-plus fs-4 d-block mb-2"></i>
                    <strong>Buat Jurnal</strong>
                    <small class="d-block text-muted">Catat kegiatan harian</small>
                </a>
            </div>

            <div class="col-md-4">
                <a href="{{ route('student.nilai') }}"
                   class="btn btn-outline-primary w-100 p-3 text-start">
                    <i class="bi bi-award fs-4 d-block mb-2"></i>
                    <strong>Nilai PKL</strong>
                    <small class="d-block text-muted">Lihat hasil penilaian</small>
                </a>
            </div>

        </div>
    </div>
</div>

@endsection
