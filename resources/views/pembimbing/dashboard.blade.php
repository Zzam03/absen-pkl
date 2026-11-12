
@extends('layouts.main')

@section('title', 'Dashboard Pembimbing - PKL Connect')
@section('page-heading', 'Dashboard Pembimbing')

@section('content')

<div class="mb-4">
    <h4 class="fw-bold mb-1">Selamat Datang, {{ auth()->user()->name }}! 👋</h4>
    <p class="text-muted mb-0">
        Pantau aktivitas siswa dan evaluasi kegiatan PKL yang kamu bimbing.
    </p>
</div>

{{-- STATISTIK --}}
<div class="row g-3 mb-4">

    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-2">Siswa Bimbingan Aktif</p>
                        <h2 class="fw-bold mb-0">{{ $jumlahSiswa }}</h2>
                    </div>
                    <div class="bg-primary-subtle rounded-3 p-3">
                        <i class="bi bi-people-fill text-primary fs-3"></i>
                    </div>
                </div>
                <small class="text-muted">Total siswa dengan penempatan aktif</small>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-2">Jurnal Menunggu</p>
                        <h2 class="fw-bold text-warning mb-0">{{ $jurnalPending }}</h2>
                    </div>
                    <div class="bg-warning-subtle rounded-3 p-3">
                        <i class="bi bi-journal-text text-warning fs-3"></i>
                    </div>
                </div>
                <small class="text-muted">Jurnal yang perlu diperiksa dan diparaf</small>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-2">Kehadiran Hari Ini</p>
                        <h2 class="fw-bold text-success mb-0">{{ $hadirHariIni }}</h2>
                    </div>
                    <div class="bg-success-subtle rounded-3 p-3">
                        <i class="bi bi-calendar-check-fill text-success fs-3"></i>
                    </div>
                </div>
                <small class="text-muted">Siswa yang tercatat hadir hari ini</small>
            </div>
        </div>
    </div>

</div>

{{-- MENU AKTIVITAS --}}
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-3">Menu Pembimbing</h5>

        <div class="row g-3">

            <div class="col-md-6">
                <a href="{{ route('pembimbing.siswa.index') }}"
                   class="btn btn-outline-primary w-100 p-3 text-start">
                    <i class="bi bi-people-fill fs-3 d-block mb-2"></i>
                    <strong>Daftar Siswa Bimbingan</strong>
                    <small class="d-block text-muted">Lihat data dan perkembangan siswa</small>
                </a>
            </div>

            <div class="col-md-6">
                <a href="{{ route('pembimbing.siswa.index') }}"
                   class="btn btn-outline-warning w-100 p-3 text-start">
                    <i class="bi bi-journal-check fs-3 d-block mb-2"></i>
                    <strong>Periksa Jurnal</strong>
                    <small class="d-block text-muted">Periksa jurnal dan berikan paraf</small>
                </a>
            </div>

        </div>
    </div>
</div>

@endsection
