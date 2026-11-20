
@extends('layouts.main')

@section('title', 'Dashboard Siswa - PKL Connect')
@section('page-heading', 'Dashboard Siswa')

@section('content')

<style>
    .student-welcome {
        background: linear-gradient(120deg, #14213d, #2563eb);
        border-radius: 18px;
        padding: 26px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }

    .student-welcome p {
    color: #ffffff !important;
    opacity: .9;
}

.student-welcome h4 {
    color: #ffffff !important;
    font-weight: 800;
    text-shadow: 0 2px 4px rgba(0,0,0,.2);
}

    .student-welcome-icon {
        width: 64px;
        height: 64px;
        border-radius: 18px;
        background: rgba(255,255,255,.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
    }

    .student-card {
        border: 1px solid #e8edf5;
        border-radius: 16px;
        background: #fff;
        height: 100%;
        transition: .2s ease;
    }

    .student-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(15, 23, 42, .07) !important;
    }

    .student-card-icon {
        width: 48px;
        height: 48px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .student-section-title {
        font-size: 17px;
        font-weight: 700;
        color: #14213d;
    }

    .student-placement {
        border: 1px solid #e8edf5;
        border-radius: 16px;
        background: #fff;
    }

    .student-quick-link {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 17px;
        border: 1px solid #e8edf5;
        border-radius: 14px;
        text-decoration: none;
        color: #14213d;
        height: 100%;
        transition: .2s ease;
    }

    .student-quick-link:hover {
        border-color: #2563eb;
        background: #f8faff;
        color: #2563eb;
        transform: translateY(-2px);
    }

    .student-quick-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
        flex-shrink: 0;
    }

    @media (max-width: 576px) {
        .student-welcome {
            padding: 21px;
        }

        .student-welcome-icon {
            width: 48px;
            height: 48px;
            font-size: 23px;
        }

        .student-welcome h4 {
            font-size: 19px;
        }

        .student-card .card-body {
            padding: 17px !important;
        }

        .student-placement .card-body {
            padding: 18px !important;
        }
    }
</style>

{{-- SAPAAN --}}
<div class="student-welcome mb-4">
    <div class="d-flex align-items-center justify-content-between gap-3">
        <div>
            <span class="badge rounded-pill mb-3"
                  style="background: rgba(255,255,255,.16); color: #fff;">
                <i class="bi bi-mortarboard me-1"></i>
                Area Siswa PKL
            </span>

            <h4 class="fw-bold mb-2">
                Halo, {{ auth()->user()->name }}! 👋
            </h4>

            <p class="mb-0">
                Selamat datang kembali. Pantau aktivitas dan perkembangan PKL kamu di sini.
            </p>
        </div>

        <div class="student-welcome-icon d-none d-sm-flex">
            <i class="bi bi-person-workspace"></i>
        </div>
    </div>
</div>

{{-- INFORMASI PENEMPATAN --}}
<div class="student-placement shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="student-card-icon"
                         style="background:#eff6ff; color:#2563eb;">
                        <i class="bi bi-building"></i>
                    </div>

                    <div>
                        <h6 class="fw-bold mb-0" style="color:#14213d;">
                            Informasi Penempatan
                        </h6>
                        <small class="text-muted">Detail lokasi praktik kerja lapangan</small>
                    </div>
                </div>

                @if($placement)
                    <h5 class="fw-bold mb-2" style="color:#14213d;">
                        {{ $placement->location->name ?? 'Lokasi belum tersedia' }}
                    </h5>

                    <div class="d-flex flex-column gap-2">
                        <div class="text-muted">
                            <i class="bi bi-person-badge me-2"></i>
                            Pembimbing: {{ $placement->pembimbing->name ?? '-' }}
                        </div>

                        <div class="text-muted">
                            <i class="bi bi-calendar3 me-2"></i>
                            Periode:
                            {{ $placement->start_date->format('d M Y') }}
                            -
                            {{ $placement->end_date->format('d M Y') }}
                        </div>
                    </div>

                    <span class="badge rounded-pill mt-3 px-3 py-2"
                          style="background:#ecfdf5; color:#047857;">
                        <i class="bi bi-check-circle-fill me-1"></i>
                        Penempatan Aktif
                    </span>
                @else
                    <h5 class="fw-bold mb-2" style="color:#14213d;">
                        Belum Ada Penempatan
                    </h5>

                    <p class="text-muted mb-0">
                        Silakan hubungi administrator untuk mendapatkan informasi penempatan PKL.
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- STATISTIK --}}
<div class="mb-3">
    <h5 class="student-section-title mb-1">Ringkasan Aktivitas</h5>
    <p class="text-muted small mb-0">Informasi aktivitas PKL kamu hari ini</p>
</div>

<div class="row g-3 mb-4">

    {{-- ABSENSI --}}
    <div class="col-12 col-md-4">
        <div class="student-card shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="text-muted small fw-semibold mb-2">Absensi Hari Ini</p>

                        <h5 class="fw-bold mb-0" style="color:#14213d;">
                            {{ $absenHariIni ? 'Sudah Absen' : 'Belum Absen' }}
                        </h5>
                    </div>

                    <div class="student-card-icon"
                         style="background:#ecfdf5; color:#059669;">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                </div>

                <div class="small text-muted">
                    @if($absenHariIni)
                        <div class="mb-1">
                            <i class="bi bi-box-arrow-in-right me-1"></i>
                            Masuk:
                            {{ $absenHariIni->check_in_time ? substr($absenHariIni->check_in_time, 0, 5) : '-' }}
                        </div>

                        <div>
                            <i class="bi bi-box-arrow-right me-1"></i>
                            Pulang:
                            {{ $absenHariIni->check_out_time ? substr($absenHariIni->check_out_time, 0, 5) : '-' }}
                        </div>
                    @else
                        Kamu belum melakukan absensi hari ini.
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- JURNAL PENDING --}}
    <div class="col-12 col-md-4">
        <div class="student-card shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="text-muted small fw-semibold mb-2">Jurnal Menunggu</p>

                        <h3 class="fw-bold mb-0" style="color:#14213d;">
                            {{ $jurnalPending }}
                        </h3>
                    </div>

                    <div class="student-card-icon"
                         style="background:#fffbeb; color:#d97706;">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>

                <small class="text-muted">
                    Jurnal menunggu paraf pembimbing
                </small>
            </div>
        </div>
    </div>

    {{-- JURNAL REVISI --}}
    <div class="col-12 col-md-4">
        <div class="student-card shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="text-muted small fw-semibold mb-2">Jurnal Perlu Revisi</p>

                        <h3 class="fw-bold mb-0" style="color:#14213d;">
                            {{ $jurnalRevisi }}
                        </h3>
                    </div>

                    <div class="student-card-icon"
                         style="background:#fef2f2; color:#dc2626;">
                        <i class="bi bi-exclamation-circle"></i>
                    </div>
                </div>

                <small class="text-muted">
                    Periksa dan perbaiki jurnal kamu
                </small>
            </div>
        </div>
    </div>

</div>

{{-- MENU CEPAT --}}
<div class="mb-3">
    <h5 class="student-section-title mb-1">Menu Cepat</h5>
    <p class="text-muted small mb-0">Akses fitur yang sering kamu gunakan</p>
</div>

<div class="row g-3 mb-4">

    <div class="col-12 col-md-4">
        <a href="{{ route('student.absen') }}" class="student-quick-link">
            <div class="student-quick-icon">
                <i class="bi bi-calendar-check"></i>
            </div>

            <div class="flex-grow-1">
                <div class="fw-bold mb-1">Absensi</div>
                <small class="text-muted">Catat kehadiran PKL</small>
            </div>

            <i class="bi bi-chevron-right text-muted"></i>
        </a>
    </div>

    <div class="col-12 col-md-4">
        <a href="{{ route('student.jurnal.create') }}" class="student-quick-link">
            <div class="student-quick-icon">
                <i class="bi bi-journal-plus"></i>
            </div>

            <div class="flex-grow-1">
                <div class="fw-bold mb-1">Buat Jurnal</div>
                <small class="text-muted">Catat kegiatan harian</small>
            </div>

            <i class="bi bi-chevron-right text-muted"></i>
        </a>
    </div>

    <div class="col-12 col-md-4">
        <a href="{{ route('student.nilai') }}" class="student-quick-link">
            <div class="student-quick-icon">
                <i class="bi bi-award"></i>
            </div>

            <div class="flex-grow-1">
                <div class="fw-bold mb-1">Nilai PKL</div>
                <small class="text-muted">Lihat hasil penilaian</small>
            </div>

            <i class="bi bi-chevron-right text-muted"></i>
        </a>
    </div>

</div>

@endsection
