
@extends('layouts.main')

@section('title', 'Absensi PKL - PKL Connect')
@section('page-heading', 'Absensi PKL')

@section('content')
<div class="container-fluid px-0 attendance-page">

    @php
        $jadwalLibur = $placement
            ? \App\Models\Schedule::where('placement_id', $placement->id)
                ->whereDate('date', today())
                ->where('status', 'libur')
                ->exists()
            : false;
    @endphp

    <div class="mb-4 attendance-intro">
        <h4 class="fw-bold mb-1">Absensi PKL</h4>
        <p class="text-muted mb-0">Catat kehadiran dan kepulanganmu sesuai lokasi PKL.</p>
    </div>

    @if(!$placement)
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            Kamu tidak memiliki penempatan PKL aktif hari ini.
        </div>

    @elseif($jadwalLibur)
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="text-center py-4">
                <div class="rounded-circle bg-danger bg-opacity-10 d-inline-flex align-items-center justify-content-center p-4 mb-3">
                    <i class="bi bi-calendar-x text-danger" style="font-size: 3rem;"></i>
                </div>

                <h4 class="fw-bold mb-2" style="color:#14213d;">
                    Hari Ini Jadwal Libur
                </h4>

                <p class="text-muted mb-3">
                    Kamu tidak perlu melakukan absensi hari ini.
                    Silakan gunakan waktu libur dengan baik.
                </p>

                <span class="badge rounded-pill px-3 py-2"
                      style="background:#fef2f2;color:#dc2626;">
                    <i class="bi bi-calendar-check me-1"></i>
                    LIBUR PKL
                </span>
            </div>
        </div>

    @elseif($attendance && in_array($attendance->status, ['izin', 'sakit']))
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="text-center">
                <i class="bi bi-calendar-check text-success fs-1"></i>
                <h5 class="fw-bold mt-3">Absensi Sudah Tercatat</h5>
                <p class="text-muted mb-2">Status kehadiran hari ini:</p>
                <span class="badge bg-info text-dark fs-6">
                    {{ ucfirst($attendance->status) }}
                </span>
            </div>
        </div>

    @elseif($attendance && $attendance->check_out_time)
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="text-center">
                <i class="bi bi-check-circle-fill text-success fs-1"></i>
                <h5 class="fw-bold mt-3">Absensi Hari Ini Selesai</h5>
                <p class="text-muted mb-1">Absen masuk: {{ $attendance->check_in_time }}</p>
                <p class="text-muted mb-0">Absen pulang: {{ $attendance->check_out_time }}</p>
            </div>
        </div>

    @else
{{-- RINGKASAN ABSENSI KHUSUS MOBILE --}}
        <div class="mobile-attendance-summary">
            <div class="mobile-summary-heading">
                <div>
                    <span class="mobile-summary-eyebrow">PKL CONNECT</span>
                    <h6 class="fw-bold mb-0">Ringkasan Absensi</h6>
                </div>
                <div class="mobile-summary-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>
            </div>

            <div class="mobile-summary-grid">
                <div class="mobile-summary-item">
                    <i class="bi bi-calendar3"></i>
                    <div>
                        <small>Tanggal</small>
                        <strong>{{ now()->translatedFormat('d M Y') }}</strong>
                    </div>
                </div>

                <div class="mobile-summary-item">
                    <i class="bi bi-building"></i>
                    <div>
                        <small>Tempat PKL</small>
                        <strong>{{ $placement->location->name ?? 'Lokasi PKL' }}</strong>
                    </div>
                </div>
            </div>

            @if($attendance && $attendance->check_in_time)
                <div class="mobile-summary-status">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Absen masuk tercatat pukul
                        <strong>{{ $attendance->check_in_time }}</strong>
                    </span>
                </div>
            @else
                <div class="mobile-summary-status">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>Pastikan GPS ditemukan dan wajah terdeteksi sebelum mengirim absensi.</span>
                </div>
            @endif

            <div class="mobile-summary-footer">
                <i class="bi bi-shield-check"></i>
                <span>Absensi tercatat berdasarkan lokasi dan foto wajah.</span>
            </div>
        </div>

        <div class="row g-4">

            {{-- INFORMASI PENEMPATAN --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 p-3 bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-building fs-3"></i>
                            </div>

                            <div>
                                <small class="text-muted">Lokasi PKL</small>
                                <h6 class="fw-bold mb-0">
                                    {{ $placement->location->name ?? 'Lokasi PKL' }}
                                </h6>
                                <small class="text-muted">
                                    {{ $placement->location->address ?? '-' }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(!$attendance || !$attendance->check_in_time)
            {{-- TOMBOL PEMBUKA KAMERA --}}
            <div class="col-12 col-lg-7 attendance-camera-mobile-hidden">
                <div class="card border-0 shadow-sm rounded-4 attendance-camera-card h-100">
                    <div class="card-body p-3 p-md-4 text-center">
                        <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                            <i class="bi bi-camera-video-fill text-primary fs-4"></i>
                            <h5 class="fw-bold mb-0">Foto Kehadiran</h5>
                        </div>
                        <p class="text-muted small mb-3">
                            Kamera akan terbuka saat kamu menekan tombol absensi.
                        </p>
                        <button type="button"
                                id="openCameraModalTop"
                                class="btn btn-primary btn-lg rounded-3 px-4"
                                disabled>
                            <i class="bi bi-camera-fill me-2"></i>
                            Buka Kamera & Absensi
                        </button>
                    </div>
                </div>
            </div>
            @endif

            {{-- GPS, PETA, DAN FORM --}}
            <div class="col-12 {{ $attendance && $attendance->check_in_time ? 'col-lg-8 mx-lg-auto' : 'col-lg-5' }}">
                <div class="card border-0 shadow-sm rounded-4 h-100 attendance-location-card">
                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-3">Lokasi Kehadiran</h5>

                        <div class="rounded-3 bg-light p-3 mb-3">
                            <div class="gps-layout">
                                <div class="gps-info">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="bi bi-geo-alt-fill text-primary fs-5"></i>
                                        <strong>Status GPS</strong>
                                    </div>

                                    <div id="locationStatus" class="small text-muted">
                                        Sedang mencari lokasi...
                                    </div>

                                    <button type="button"
                                            id="refreshLocation"
                                            class="btn btn-sm btn-outline-primary mt-3">
                                        <i class="bi bi-arrow-clockwise me-1"></i>
                                        Perbarui Lokasi
                                    </button>
                                </div>

                                <div id="attendanceMap" class="attendance-map"></div>
                            </div>
                        </div>

                        <div class="attendance-date mb-3">
                            <small class="text-muted d-block">Tanggal</small>
                            <strong>{{ now()->translatedFormat('d F Y') }}</strong>
                        </div>

                        @if($attendance && $attendance->check_in_time)
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                Absen masuk tercatat pukul
                                <strong>{{ $attendance->check_in_time }}</strong>
                            </div>
                        @endif

                        <form id="attendanceForm"
                              method="POST"
                              action="{{ $attendance && $attendance->check_in_time ? route('student.absen.pulang') : route('student.absen.masuk') }}"
                              enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">

                            @if($attendance && $attendance->check_in_time)
                                <button type="submit"
                                        id="submitCheckoutDirect"
                                        class="btn btn-primary btn-lg w-100 rounded-3"
                                        disabled>
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    Absen Pulang
                                </button>
                            @else
                                <button type="button"
                                        id="openCameraModal"
                                        class="btn btn-primary btn-lg w-100 rounded-3"
                                        disabled>
                                    <i class="bi bi-camera-fill me-2"></i>
                                    Absen Masuk
                                </button>
                            @endif

                            <p class="text-muted small text-center mt-3 mb-0">
                                Pastikan GPS ditemukan sebelum membuka kamera.
                            </p>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        {{-- POPUP KAMERA DAN DETEKSI WAJAH --}}
        <div class="modal fade" id="attendanceCameraModal" tabindex="-1"
             aria-labelledby="attendanceCameraModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content border-0 rounded-4 shadow">
                    <div class="modal-header border-0 pb-0">
                        <div>
                            <h5 class="modal-title fw-bold" id="attendanceCameraModalLabel">
                                Foto Kehadiran
                            </h5>
                            <p class="small text-muted mb-0">
                                Posisikan wajah di tengah lingkaran kamera.
                            </p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body text-center pt-3">
                        <div class="camera-frame position-relative bg-dark overflow-hidden mx-auto mb-3">
                            <video id="cameraVideo" autoplay playsinline muted></video>

                            <div id="cameraPlaceholder"
                                 class="position-absolute top-50 start-50 translate-middle text-center text-white w-100 px-3">
                                <i class="bi bi-camera-video fs-1"></i>
                                <p class="mt-2 mb-0">Menyiapkan kamera...</p>
                            </div>

                            <img id="photoPreview" alt="Pratinjau foto absensi">
                            <canvas id="cameraCanvas" class="d-none"></canvas>
                        </div>

                        <div id="faceAlert" class="alert alert-secondary py-2 small mb-3" role="status">
                            <i class="bi bi-info-circle me-1"></i>
                            Kamera akan aktif otomatis saat popup dibuka.
                        </div>

                        <div id="cameraMessage" class="small text-muted mb-3">
                            Gunakan kamera depan dan pastikan wajah terlihat jelas.
                        </div>

                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <button type="button" id="startCamera" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-clockwise me-1"></i>
                                Aktifkan Ulang
                            </button>

                            <button type="button" id="takePhoto" class="btn btn-primary" disabled>
                                <i class="bi bi-camera-fill me-1"></i>
                                Ambil Foto
                            </button>
                        </div>

                        <input type="file"
                               id="photoInput"
                               name="photo"
                               form="attendanceForm"
                               accept="image/jpeg,image/png"
                               class="d-none">

                        <small class="text-muted d-block mt-3">
                            <i class="bi bi-shield-check me-1"></i>
                            Foto diambil langsung dari kamera. Ukuran maksimal 4 MB.
                        </small>
                    </div>

                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit"
                                id="submitAttendance"
                                form="attendanceForm"
                                class="btn btn-primary"
                                disabled>
                            <i class="bi bi-fingerprint me-2"></i>
                            {{ $attendance && $attendance->check_in_time ? 'Kirim Absen Pulang' : 'Kirim Absen Masuk' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

    @endif
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

<style>
    .camera-frame {
        width: 220px;
        height: 220px;
        aspect-ratio: 1 / 1;
        border-radius: 50%;
        background: #111827;
    }

    #attendanceCameraModal .modal-content {
        overflow: hidden;
    }

    #attendanceCameraModal .modal-body {
        overflow-x: hidden;
    }

    #cameraVideo {
        display: none;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: scaleX(-1);
        border-radius: 50%;
    }

    #photoPreview {
        display: none;
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        background: #111827;
        border-radius: 50%;
    }


    /* Peta mini di sebelah kanan informasi GPS */
    .gps-layout {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .gps-info {
        flex: 1;
        min-width: 0;
    }

    .attendance-map {
        flex: 0 0 120px;
        width: 120px;
        height: 120px;
        border-radius: 12px;
        background: #e5e7eb;
        z-index: 1;
        overflow: hidden;
    }

    .leaflet-container {
        font-family: inherit;
    }

    /* Ringkasan hanya ditampilkan pada layar HP */
    .mobile-attendance-summary {
        display: none;
    }
    /* TAMPILAN LAPTOP DAN DESKTOP */
    @media (min-width: 768px) {
        .attendance-camera-card {
            min-height: 100%;
        }

        .attendance-camera-card .card-body {
            min-height: 320px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 35px !important;
        }

        .attendance-camera-card h5 {
            color: #14213d;
            font-size: 21px;
        }

        .attendance-camera-card p {
            max-width: 390px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }

        .attendance-camera-card #openCameraModalTop {
            min-width: 250px;
            padding: 13px 25px;
            font-size: 15px;
            font-weight: 600;
        }

        .attendance-page .col-lg-5 > .card {
            height: 100%;
        }

        .attendance-page .col-lg-5 .card-body {
            padding: 25px !important;
        }

        .gps-layout {
            align-items: center;
            gap: 16px;
        }

        .attendance-map {
            flex: 0 0 150px;
            width: 150px;
            height: 150px;
            border-radius: 14px;
        }

        .attendance-page .col-lg-5 .card-body > h5 {
            color: #14213d;
            font-size: 19px;
        }
    }

    /* PENYEMPURNAAN KHUSUS LAPTOP DAN DESKTOP */
    @media (min-width: 992px) {
        .attendance-page > .row.g-4 {
            align-items: stretch;
            --bs-gutter-x: 24px;
            --bs-gutter-y: 24px;
        }

        .attendance-page .row.g-4 > .col-lg-7,
        .attendance-page .row.g-4 > .col-lg-5,
        .attendance-page .row.g-4 > .col-lg-8 {
            display: flex;
        }

        .attendance-page .attendance-camera-card,
        .attendance-page .attendance-location-card {
            width: 100%;
            min-height: 360px;
            border-radius: 18px !important;
        }

        .attendance-page .attendance-camera-card .card-body {
            min-height: 360px;
            padding: 40px !important;
        }

        .attendance-page .attendance-location-card .card-body {
            display: flex;
            flex-direction: column;
            padding: 28px !important;
        }

        .attendance-page .attendance-location-card h5 {
            margin-bottom: 20px !important;
            font-size: 20px;
            color: #14213d;
        }

        .attendance-page .attendance-location-card .gps-layout {
            gap: 20px;
            align-items: center;
        }

        .attendance-page .attendance-location-card .gps-info {
            flex: 1 1 auto;
            min-width: 0;
        }

        .attendance-page .attendance-location-card #attendanceMap {
            flex: 0 0 160px;
            width: 160px;
            height: 160px;
            border-radius: 15px;
        }

        .attendance-page .attendance-location-card .rounded-3.bg-light {
            padding: 18px !important;
            border: 1px solid #e8edf5;
        }

        .attendance-page .attendance-location-card #locationStatus {
            line-height: 1.6;
            overflow-wrap: anywhere;
        }

        .attendance-page .attendance-location-card #refreshLocation {
            padding: 8px 13px;
            border-radius: 9px;
        }

        .attendance-page .attendance-location-card #attendanceForm {
            margin-top: auto;
        }

        .attendance-page .attendance-location-card #attendanceForm .btn {
            min-height: 48px;
            border-radius: 11px !important;
            font-weight: 600;
        }

        .attendance-page .attendance-location-card .attendance-date {
            margin-bottom: 16px !important;
        }

        .attendance-page .attendance-location-card > .card-body > .alert {
            border-radius: 11px;
        }

        /* Setelah absen masuk, kartu lokasi tampil proporsional di tengah */
        .attendance-page .col-lg-8.mx-lg-auto .attendance-location-card {
            min-height: 330px;
        }

        .attendance-page .col-lg-8.mx-lg-auto .attendance-location-card .card-body {
            padding: 32px 36px !important;
        }
    }
    /* TAMPILAN MOBILE */
    @media (max-width: 767.98px) {
        .mobile-attendance-summary { display: block; }
        .attendance-page > .row { margin-bottom: 0; }
        .attendance-page .card-body { padding: 12px !important; }
        .attendance-page {
            overflow-x: hidden;
            padding-bottom: 5px !important;
        }

        .attendance-intro {
            display: none;
        }

        /* Sembunyikan informasi penempatan yang panjang */
        .attendance-page > .row > .col-12:first-child {
            display: none;
        }

        .attendance-page .row.g-4 {
            --bs-gutter-x: 8px;
            --bs-gutter-y: 8px;
        }

        .attendance-page .row > .col-12 {
            width: 100%;
        }

        .attendance-page .card {
            height: auto !important;
            border-radius: 14px !important;
        }

        .attendance-page .card-body {
            padding: 9px 12px !important;
        }

        /* Ukuran kamera bulat diatur oleh modal. */

        /* Informasi deteksi wajah */
        #faceAlert {
            font-size: 10px;
            padding: 6px 8px;
            margin-bottom: 6px !important;
        }

        #cameraMessage {
            display: none;
        }

        /* Tombol kamera */
        #startCamera,
        #takePhoto {
            flex: 1;
            font-size: 11px;
            padding: 8px 4px;
        }

        /* Hilangkan keterangan tambahan kamera */
        .attendance-page .card-body > small.text-muted {
            display: none;
        }

        /* Peta mini di kanan koordinat */
        .gps-layout {
            gap: 8px;
            align-items: center;
        }

        .attendance-map {
            flex: 0 0 84px;
            width: 84px;
            height: 84px;
            border-radius: 10px;
        }

        .attendance-page .col-lg-5 .card-body > h5 {
            margin-bottom: 6px !important;
        }

        .attendance-page .rounded-3.bg-light.p-3 {
            padding: 8px !important;
            margin-bottom: 6px !important;
        }

        .attendance-page .rounded-3.bg-light.p-3 .d-flex {
            margin-bottom: 3px !important;
        }

        #locationStatus {
            font-size: 10px;
            line-height: 1.3;
            overflow-wrap: anywhere;
        }

        #refreshLocation {
            margin-top: 5px !important;
            padding: 4px 8px;
            font-size: 10px;
        }

        /* Penyesuaian khusus tampilan HP */
        .attendance-camera-mobile-hidden {
            display: none !important;
        }

        #attendanceMap {
            display: none !important;
        }

        /* Tanggal disembunyikan di HP untuk menghemat ruang */
        .attendance-date {
            display: none;
        }

        #attendanceForm {
            margin-top: 5px;
        }

        #attendanceForm .btn-lg {
            font-size: 13px;
            padding: 10px;
        }

        #attendanceForm p {
            display: none;
        }

        .attendance-page .col-lg-5 .card-body > .alert {
            padding: 6px;
            margin-bottom: 6px;
            font-size: 10px;
        }
.mobile-attendance-summary {
                margin-top: 12px;
                padding: 16px;
                border: 1px solid #dbeafe;
                border-radius: 17px;
                background: linear-gradient(145deg, #f8fbff 0%, #eef5ff 100%);
            }

            .mobile-summary-heading {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 14px;
            }

            .mobile-summary-eyebrow {
                display: block;
                margin-bottom: 4px;
                color: #2563eb;
                font-size: 9px;
                font-weight: 800;
                letter-spacing: 1.4px;
            }

            .mobile-summary-heading h6 {
                color: #14213d;
                font-size: 14px;
            }

            .mobile-summary-icon {
                display: flex;
                width: 38px;
                height: 38px;
                align-items: center;
                justify-content: center;
                border-radius: 12px;
                background: #dbeafe;
                color: #2563eb;
                font-size: 19px;
            }

            .mobile-summary-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 9px;
            }

            .mobile-summary-item {
                display: flex;
                min-width: 0;
                align-items: flex-start;
                gap: 9px;
                padding: 11px 9px;
                border: 1px solid #e5edf9;
                border-radius: 12px;
                background: #fff;
            }

            .mobile-summary-item > i {
                color: #2563eb;
                font-size: 16px;
                margin-top: 2px;
            }

            .mobile-summary-item small {
                display: block;
                margin-bottom: 3px;
                color: #64748b;
                font-size: 10px;
            }

            .mobile-summary-item strong {
                display: block;
                overflow-wrap: anywhere;
                color: #14213d;
                font-size: 11px;
                line-height: 1.35;
            }

            .mobile-summary-status {
                display: flex;
                align-items: flex-start;
                gap: 8px;
                margin-top: 10px;
                padding: 11px;
                border-radius: 11px;
                background: #fff;
                color: #334155;
                font-size: 11px;
                line-height: 1.5;
            }

            .mobile-summary-status i {
                flex-shrink: 0;
                color: #2563eb;
                font-size: 15px;
            }

            .mobile-summary-footer {
                display: flex;
                align-items: center;
                gap: 7px;
                margin-top: 12px;
                color: #64748b;
                font-size: 10px;
            }

            .mobile-summary-footer i {
                color: #16a34a;
                font-size: 13px;
            }

    }

    /* Perapian tambahan HP: kurangi ruang vertikal */
    @media (max-width: 767.98px) {
        .attendance-page .row.g-4 {
            --bs-gutter-y: 6px;
        }

        .attendance-page .card-body {
            padding-top: 8px !important;
            padding-bottom: 8px !important;
        }

        .mobile-attendance-summary {
            margin-top: 8px !important;
            padding: 11px !important;
        }

        .mobile-summary-heading {
            margin-bottom: 8px !important;
        }

        .mobile-summary-item {
            padding: 8px !important;
        }

        .mobile-summary-status {
            margin-top: 7px !important;
            padding: 8px !important;
        }

        .mobile-summary-footer {
            margin-top: 7px !important;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api@1.7.15/dist/face-api.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const video = document.getElementById('cameraVideo');
    const canvas = document.getElementById('cameraCanvas');
    const preview = document.getElementById('photoPreview');
    const placeholder = document.getElementById('cameraPlaceholder');
    const photoInput = document.getElementById('photoInput');
    const startButton = document.getElementById('startCamera');
    const takeButton = document.getElementById('takePhoto');
    const cameraMessage = document.getElementById('cameraMessage');
    const faceAlert = document.getElementById('faceAlert');

    const locationStatus = document.getElementById('locationStatus');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const refreshLocation = document.getElementById('refreshLocation');
    const submitButton = document.getElementById('submitAttendance');
    const openCameraButton = document.getElementById('openCameraModal');
    const openCameraTopButton = document.getElementById('openCameraModalTop');
    const cameraModalElement = document.getElementById('attendanceCameraModal');
    const cameraModal = cameraModalElement && window.bootstrap
        ? bootstrap.Modal.getOrCreateInstance(cameraModalElement)
        : null;
    const form = document.getElementById('attendanceForm');
    const checkoutButton = document.getElementById('submitCheckoutDirect');
    const requiresPhoto = @json(!($attendance && $attendance->check_in_time));

    if (!form) return;

    let cameraStream = null;
    let locationReady = false;
    let faceModelReady = false;
    let faceDetected = false;
    let photoReady = false;
    let faceVerifiedAtCapture = false;
    let detectionTimer = null;
    let map = null;
    let userMarker = null;
    let pklMarker = null;

    const pklLatitude = @json($placement->location->latitude ?? null);
    const pklLongitude = @json($placement->location->longitude ?? null);
    const pklName = @json($placement->location->name ?? 'Lokasi PKL');
    const pklRadius = Number(@json($placement->location->radius_meter ?? 100));

    function updateSubmitButton() {
        if (openCameraButton) {
            openCameraButton.disabled = !locationReady;
        }
        if (openCameraTopButton) {
            openCameraTopButton.disabled = !locationReady;
        }
        if (requiresPhoto) {
            submitButton.disabled = !(locationReady && photoReady && faceVerifiedAtCapture);
        }

        if (checkoutButton) {
            checkoutButton.disabled = !locationReady;
        }
    }

    function showFaceStatus(type, message) {
        faceAlert.className = 'alert py-2 small mb-3';

        if (type === 'success') {
            faceAlert.classList.add('alert-success');
            faceAlert.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>' + message;
        } else if (type === 'danger') {
            faceAlert.classList.add('alert-danger');
            faceAlert.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-1"></i>' + message;
        } else {
            faceAlert.classList.add('alert-secondary');
            faceAlert.innerHTML = '<i class="bi bi-info-circle me-1"></i>' + message;
        }
    }

    async function loadFaceModel() {
        if (faceModelReady) return true;

        if (!window.faceapi) {
            showFaceStatus('danger', 'Sistem deteksi wajah tidak tersedia.');
            return false;
        }

        try {
            showFaceStatus('info', 'Sedang memuat sistem deteksi wajah...');

            const modelUrl = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api@1.7.15/model';

            await faceapi.nets.tinyFaceDetector.loadFromUri(modelUrl);

            faceModelReady = true;
            return true;
        } catch (error) {
            console.error('Face model error:', error);
            showFaceStatus('danger', 'Model deteksi wajah gagal dimuat. Muat ulang halaman dan coba lagi.');
            return false;
        }
    }

    async function checkFace() {
        if (!cameraStream || !faceModelReady || video.readyState < 2) return;

        try {
            const detections = await faceapi.detectAllFaces(
                video,
                new faceapi.TinyFaceDetectorOptions({
                    inputSize: 320,
                    scoreThreshold: 0.5
                })
            );

            const found = detections.length > 0;
            faceDetected = found;

            if (found) {
                takeButton.disabled = false;
                showFaceStatus('success', 'Wajah terdeteksi. Kamu sudah bisa mengambil foto.');
            } else {
                takeButton.disabled = true;
                showFaceStatus('danger', 'Wajah belum terdeteksi. Posisikan wajah di depan kamera.');
            }
        } catch (error) {
            console.error('Face detection error:', error);
            faceDetected = false;
            takeButton.disabled = true;
            showFaceStatus('danger', 'Pemeriksaan wajah mengalami kendala. Coba aktifkan ulang kamera.');
        }
    }

    async function startCamera() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            cameraMessage.textContent = 'Browser tidak mendukung kamera. Gunakan Chrome melalui localhost atau HTTPS.';
            showFaceStatus('danger', 'Akses kamera tidak tersedia di browser ini.');
            return;
        }

        startButton.disabled = true;
        takeButton.disabled = true;
        faceDetected = false;
        photoReady = false;
        faceVerifiedAtCapture = false;
        updateSubmitButton();

        try {
            if (cameraStream) {
                cameraStream.getTracks().forEach(track => track.stop());
            }

            if (detectionTimer) {
                clearInterval(detectionTimer);
                detectionTimer = null;
            }

            const modelLoaded = await loadFaceModel();

            if (!modelLoaded) {
                startButton.disabled = false;
                return;
            }

            cameraStream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'user',
                    width: { ideal: 640 },
                    height: { ideal: 480 }
                },
                audio: false
            });

            video.srcObject = cameraStream;
            video.style.display = 'block';
            placeholder.style.display = 'none';
            preview.style.display = 'none';

            await video.play();

            cameraMessage.textContent = 'Kamera aktif. Tunggu sampai wajah terdeteksi.';
            showFaceStatus('info', 'Sedang memeriksa wajah...');

            await checkFace();
            detectionTimer = setInterval(checkFace, 500);

        } catch (error) {
            console.error('Camera error:', error);

            let errorMessage = 'Kamera gagal diakses.';

            if (error.name === 'NotAllowedError') {
                errorMessage = 'Izin kamera ditolak. Izinkan akses kamera melalui pengaturan browser.';
            } else if (error.name === 'NotFoundError') {
                errorMessage = 'Kamera tidak ditemukan pada perangkat.';
            } else if (error.name === 'NotReadableError') {
                errorMessage = 'Kamera sedang digunakan aplikasi lain. Tutup aplikasi video call atau aplikasi lain yang memakai kamera.';
            } else if (!window.isSecureContext) {
                errorMessage = 'Akses kamera membutuhkan HTTPS atau localhost.';
            }

            cameraMessage.textContent = errorMessage;
            showFaceStatus('danger', errorMessage);
        } finally {
            startButton.disabled = false;
        }
    }

    startButton.addEventListener('click', startCamera);

    if (openCameraButton && cameraModal) {
        openCameraButton.addEventListener('click', function () {
            cameraModal.show();
        });

        if (openCameraTopButton) {
            openCameraTopButton.addEventListener('click', function () {
                cameraModal.show();
            });
        }

        cameraModalElement.addEventListener('shown.bs.modal', function () {
            if (!cameraStream) {
                startCamera();
            }
            if (map) {
                setTimeout(() => map.invalidateSize(), 200);
            }
        });

        cameraModalElement.addEventListener('hidden.bs.modal', function () {
            if (cameraStream) {
                cameraStream.getTracks().forEach(track => track.stop());
                cameraStream = null;
            }

            if (detectionTimer) {
                clearInterval(detectionTimer);
                detectionTimer = null;
            }

            video.srcObject = null;
            video.style.display = 'none';
            placeholder.style.display = 'block';
            placeholder.innerHTML = '<i class="bi bi-camera-video fs-1"></i><p class="mt-2 mb-0">Kamera belum diaktifkan</p>';
            startButton.disabled = false;
            takeButton.disabled = true;
        });
    } else if (openCameraButton) {
        openCameraButton.addEventListener('click', function () {
            alert('Popup kamera tidak dapat dibuka. Muat ulang halaman dan coba lagi.');
        });
    }

    takeButton.addEventListener('click', function () {
        if (!cameraStream || !video.videoWidth || !faceDetected) {
            alert('Wajah belum terdeteksi. Posisikan wajah di depan kamera terlebih dahulu.');
            return;
        }

        takeButton.disabled = true;

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        const context = canvas.getContext('2d');
        context.save();
        context.translate(canvas.width, 0);
        context.scale(-1, 1);
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        context.restore();

        canvas.toBlob(function (blob) {
            if (!blob) {
                cameraMessage.textContent = 'Gagal mengambil foto. Silakan coba lagi.';
                takeButton.disabled = !faceDetected;
                return;
            }

            if (blob.size > 4 * 1024 * 1024) {
                cameraMessage.textContent = 'Ukuran foto melebihi 4 MB. Coba ambil foto kembali.';
                takeButton.disabled = !faceDetected;
                return;
            }

            const photoFile = new File([blob], 'foto-absensi.jpg', {
                type: 'image/jpeg'
            });

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(photoFile);
            photoInput.files = dataTransfer.files;

            preview.src = URL.createObjectURL(blob);
            preview.style.display = 'block';

            photoReady = true;
            faceVerifiedAtCapture = true;
            updateSubmitButton();

            cameraMessage.textContent = 'Foto berhasil diambil. Periksa hasil foto sebelum mengirim absensi.';
            showFaceStatus('success', 'Wajah berhasil terdeteksi dan foto siap dikirim.');
        }, 'image/jpeg', 0.85);
    });

    function initializeMap() {
        const mapElement = document.getElementById('attendanceMap');
        if (!mapElement || !window.L) return;

        map = L.map('attendanceMap').setView([-2.5, 118], 5);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        if (pklLatitude !== null && pklLongitude !== null) {
            const lat = Number(pklLatitude);
            const lng = Number(pklLongitude);

            if (Number.isFinite(lat) && Number.isFinite(lng)) {
                pklMarker = L.marker([lat, lng])
                    .addTo(map)
                    .bindPopup('<strong>' + pklName + '</strong><br>Lokasi PKL');

                L.circle([lat, lng], {
                    radius: Number.isFinite(pklRadius) ? pklRadius : 100,
                    color: '#2563eb',
                    fillOpacity: 0.08
                }).addTo(map);

                map.setView([lat, lng], 17);
            }
        }

        setTimeout(() => map.invalidateSize(), 300);
    }

    function getLocation() {
        locationReady = false;
        updateSubmitButton();

        locationStatus.textContent = 'Sedang mencari lokasi...';

        if (!navigator.geolocation) {
            locationStatus.textContent = 'Browser tidak mendukung GPS.';
            return;
        }

        refreshLocation.disabled = true;

        navigator.geolocation.getCurrentPosition(
            function (position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                latitudeInput.value = lat;
                longitudeInput.value = lng;
                locationReady = true;

                locationStatus.innerHTML =
                    '<span class="text-success fw-semibold">' +
                    '<i class="bi bi-check-circle-fill me-1"></i> Lokasi berhasil ditemukan' +
                    '</span><br>Latitude: ' + lat.toFixed(6) +
                    '<br>Longitude: ' + lng.toFixed(6);

                if (map) {
                    if (userMarker) {
                        userMarker.setLatLng([lat, lng]);
                    } else {
                        userMarker = L.marker([lat, lng]).addTo(map);
                    }

                    userMarker.bindPopup('<strong>Posisi Kamu</strong>').openPopup();

                    if (pklMarker) {
                        const bounds = L.latLngBounds([
                            [lat, lng],
                            pklMarker.getLatLng()
                        ]);

                        map.fitBounds(bounds, {
                            padding: [20, 20],
                            maxZoom: 17
                        });
                    } else {
                        map.setView([lat, lng], 16);
                    }

                    setTimeout(() => map.invalidateSize(), 200);
                }

                updateSubmitButton();
                refreshLocation.disabled = false;
            },
            function (error) {
                locationReady = false;
                updateSubmitButton();
                refreshLocation.disabled = false;

                if (error.code === 1) {
                    locationStatus.textContent = 'Izin lokasi ditolak. Izinkan akses lokasi di browser.';
                } else if (error.code === 2) {
                    locationStatus.textContent = 'Lokasi tidak tersedia. Periksa GPS perangkat.';
                } else {
                    locationStatus.textContent = 'Waktu mencari lokasi habis. Silakan coba lagi.';
                }
            },
            {
                enableHighAccuracy: true,
                timeout: 20000,
                maximumAge: 0
            }
        );
    }

    refreshLocation.addEventListener('click', getLocation);

    form.addEventListener('submit', function (event) {
        if (!locationReady) {
            event.preventDefault();
            alert('Lokasi GPS belum berhasil ditemukan.');
            return;
        }

        if (requiresPhoto && (!photoReady || !faceVerifiedAtCapture || !photoInput.files.length)) {
            event.preventDefault();
            alert('Ambil foto melalui kamera setelah wajah terdeteksi.');
            return;
        }

        if (requiresPhoto) {
            submitButton.disabled = true;
        }

        if (checkoutButton) {
            checkoutButton.disabled = true;
            checkoutButton.innerHTML =
                '<span class="spinner-border spinner-border-sm me-2"></span> Mengirim absen pulang...';
        }
        submitButton.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2"></span> Mengirim absensi...';
    });

    initializeMap();
    getLocation();

    window.addEventListener('beforeunload', function () {
        if (cameraStream) {
            cameraStream.getTracks().forEach(track => track.stop());
        }

        if (detectionTimer) {
            clearInterval(detectionTimer);
        }
    });
});
</script>
@endpush
