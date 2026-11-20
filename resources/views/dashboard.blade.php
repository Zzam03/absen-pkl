
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

    .student-schedule {
        border: 1px solid #e8edf5;
        border-radius: 16px;
        background: #fff;
    }

    .schedule-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 16px;
        border: 1px solid #e8edf5;
        border-radius: 13px;
        margin-bottom: 10px;
    }

    .schedule-item:last-child {
        margin-bottom: 0;
    }

    .schedule-date {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .schedule-date strong {
        font-size: 17px;
        line-height: 1;
    }

    .schedule-date small {
        font-size: 10px;
        text-transform: uppercase;
        font-weight: 700;
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

        .schedule-item {
            align-items: flex-start;
            padding: 13px;
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

{{-- JADWAL PKL --}}
<div class="mb-3">
    <h5 class="student-section-title mb-1">Jadwal PKL Saya</h5>
    <p class="text-muted small mb-0">
        Jadwal terbaru yang telah ditetapkan oleh pembimbing kantor
    </p>
</div>

<div class="student-schedule shadow-sm p-3 p-md-4 mb-4">

    @if(!$placement)
        <div class="text-center py-4">
            <i class="bi bi-calendar-x fs-1 text-muted"></i>
            <h6 class="fw-bold mt-3 mb-1" style="color:#14213d;">
                Jadwal Belum Tersedia
            </h6>
            <p class="text-muted small mb-0">
                Kamu belum memiliki penempatan PKL aktif.
            </p>
        </div>

    @elseif($jadwalPkl->isEmpty())
        <div class="text-center py-4">
            <i class="bi bi-calendar-week fs-1 text-muted"></i>
            <h6 class="fw-bold mt-3 mb-1" style="color:#14213d;">
                Belum Ada Jadwal
            </h6>
            <p class="text-muted small mb-0">
                Jadwal PKL kamu belum dibuat oleh administrator.
            </p>
        </div>

    @else
        @foreach($jadwalPkl as $jadwal)
            <div class="schedule-item">
                <div class="d-flex align-items-center gap-3">
                    <div class="schedule-date">
                        <strong>{{ $jadwal->date->format('d') }}</strong>
                        <small>{{ $jadwal->date->translatedFormat('M') }}</small>
                    </div>

                    <div>
                        <div class="fw-bold" style="color:#14213d;">
                            {{ $jadwal->date->translatedFormat('l, d F Y') }}

                            @if($jadwal->date->isToday())
                                <span class="badge rounded-pill ms-1"
                                      style="background:#eff6ff;color:#2563eb;">
                                    Hari ini
                                </span>
                            @endif
                        </div>

                        @if($jadwal->status === 'libur')
                            <small class="text-muted">
                                <i class="bi bi-calendar-x me-1"></i>
                                Tidak ada kegiatan PKL
                            </small>
                        @else
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>
                                {{ substr($jadwal->start_time, 0, 5) }}
                                -
                                {{ substr($jadwal->end_time, 0, 5) }}
                            </small>
                        @endif

                        @if($jadwal->activity)
                            <div class="small text-muted mt-1">
                                {{ $jadwal->activity }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex-shrink-0">
                    @if($jadwal->status === 'libur')
                        <span class="badge rounded-pill px-3 py-2"
                              style="background:#fef2f2;color:#dc2626;">
                            <i class="bi bi-calendar-x me-1"></i>
                            Libur
                        </span>
                    @else
                        <span class="badge rounded-pill px-3 py-2"
                              style="background:#ecfdf5;color:#047857;">
                            <i class="bi bi-check-circle me-1"></i>
                            Aktif
                        </span>
                    @endif
                </div>
            </div>
        @endforeach
    @endif
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
