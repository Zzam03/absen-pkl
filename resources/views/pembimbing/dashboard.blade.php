@extends('layouts.main')

@section('title', 'Dashboard Pembimbing - PKL Connect')
@section('page-heading', 'Dashboard Pembimbing')

@section('content')

@php
    $isAgista = auth()->user()->role === 'pembimbing_sekolah';

    $namaBulan = [
        'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
        'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'
    ];

    $dataKehadiran = collect(range(1, 12))->map(function ($bulan) use ($grafikKehadiran) {
        $data = $grafikKehadiran->firstWhere('month', $bulan);
        return (int) ($data->total ?? 0);
    })->values();

    $statusJurnal = [
        (int) ($jurnalStatus['pending'] ?? 0),
        (int) ($jurnalStatus['approved'] ?? 0),
        (int) ($jurnalStatus['revision'] ?? 0),
    ];
@endphp

{{-- SAMBUTAN --}}
<div class="mb-4">
    <h4 class="fw-bold mb-1">
        Selamat Datang, {{ auth()->user()->name }}! 👋
    </h4>
    <p class="text-secondary mb-0">
        @if($isAgista)
            Pantau perkembangan siswa PKL dari sekolah SMK BPI.
        @else
            Pantau aktivitas dan evaluasi kegiatan siswa PKL yang kamu bimbing.
        @endif
    </p>
</div>

{{-- STATISTIK --}}
<div class="row g-3 mb-4">

    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-secondary mb-2">Siswa Bimbingan Aktif</p>
                        <h2 class="fw-bold mb-0">{{ $jumlahSiswa }}</h2>
                    </div>
                    <div class="bg-primary-subtle rounded-3 p-3">
                        <i class="bi bi-people-fill text-primary fs-3"></i>
                    </div>
                </div>
                <small class="text-secondary">Total siswa dengan penempatan aktif</small>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-secondary mb-2">Jurnal Menunggu</p>
                        <h2 class="fw-bold text-warning mb-0">{{ $jurnalPending }}</h2>
                    </div>
                    <div class="bg-warning-subtle rounded-3 p-3">
                        <i class="bi bi-journal-text text-warning fs-3"></i>
                    </div>
                </div>
                <small class="text-secondary">Jurnal yang perlu diperiksa</small>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-secondary mb-2">Kehadiran Hari Ini</p>
                        <h2 class="fw-bold text-success mb-0">{{ $hadirHariIni }}</h2>
                    </div>
                    <div class="bg-success-subtle rounded-3 p-3">
                        <i class="bi bi-calendar-check-fill text-success fs-3"></i>
                    </div>
                </div>
                <small class="text-secondary">Siswa yang tercatat hadir hari ini</small>
            </div>
        </div>
    </div>

</div>

{{-- GRAFIK --}}
<div class="row g-3 mb-4">

    <div class="col-12 col-xl-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="mb-3">
                    <h5 class="fw-bold mb-1">Tren Kehadiran Siswa</h5>
                    <p class="text-secondary small mb-0">
                        Jumlah kehadiran siswa selama tahun {{ date('Y') }}.
                    </p>
                </div>

                <div style="height: 300px; position: relative;">
                    <canvas id="pembimbingAttendanceChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="mb-3">
                    <h5 class="fw-bold mb-1">Status Jurnal</h5>
                    <p class="text-secondary small mb-0">
                        Ringkasan pemeriksaan jurnal siswa.
                    </p>
                </div>

                <div style="height: 260px; position: relative;">
                    <canvas id="pembimbingJournalChart"></canvas>
                </div>

                <div class="small text-secondary mt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Menunggu</span>
                        <strong>{{ $statusJurnal[0] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Disetujui</span>
                        <strong>{{ $statusJurnal[1] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Revisi</span>
                        <strong>{{ $statusJurnal[2] }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ABSENSI TERBARU --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Absensi Terbaru</h5>
                <p class="text-secondary small mb-0">
                    Aktivitas kehadiran siswa bimbingan.
                </p>
            </div>
            <span class="badge bg-primary-subtle text-primary">
                5 Data Terbaru
            </span>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nama Siswa</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensiTerbaru as $absensi)
                        @php
                            $warnaAbsensi = [
                                'hadir' => 'success',
                                'izin' => 'warning',
                                'sakit' => 'info',
                                'alpha' => 'danger',
                            ];
                            $warna = $warnaAbsensi[$absensi->status] ?? 'secondary';
                        @endphp
                        <tr>
                            <td class="fw-semibold">
                                {{ $absensi->student->name ?? 'Siswa tidak ditemukan' }}
                            </td>
                            <td>
                                {{ $absensi->date ? \Carbon\Carbon::parse($absensi->date)->format('d/m/Y') : '-' }}
                            </td>
                            <td>
                                {{ $absensi->check_in_time ? \Carbon\Carbon::parse($absensi->check_in_time)->format('H:i') : '-' }}
                            </td>
                            <td>
                                <span class="badge bg-{{ $warna }}-subtle text-{{ $warna }}">
                                    {{ ucfirst($absensi->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-secondary py-4">
                                Belum ada data absensi siswa.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

{{-- JURNAL TERBARU --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Jurnal Harian Terbaru</h5>
                <p class="text-secondary small mb-0">
                    Laporan kegiatan siswa yang terbaru.
                </p>
            </div>
            <span class="badge bg-primary-subtle text-primary">
                5 Data Terbaru
            </span>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nama Siswa</th>
                        <th>Tanggal</th>
                        <th>Kegiatan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jurnalTerbaru as $jurnal)
                        @php
                            $warnaJurnal = [
                                'pending' => 'warning',
                                'approved' => 'success',
                                'revision' => 'danger',
                            ];
                            $warna = $warnaJurnal[$jurnal->status] ?? 'secondary';
                        @endphp
                        <tr>
                            <td class="fw-semibold">
                                {{ $jurnal->student->name ?? 'Siswa tidak ditemukan' }}
                            </td>
                            <td>
                                {{ $jurnal->date ? \Carbon\Carbon::parse($jurnal->date)->format('d/m/Y') : '-' }}
                            </td>
                            <td style="min-width: 200px;">
                                {{ \Illuminate\Support\Str::limit($jurnal->activity, 65) }}
                            </td>
                            <td>
                                <span class="badge bg-{{ $warna }}-subtle text-{{ $warna }}">
                                    {{ ucfirst($jurnal->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-secondary py-4">
                                Belum ada jurnal siswa.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

{{-- MENU PEMBIMBING --}}
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-3">Menu Pembimbing</h5>

        <div class="row g-3">

            <div class="col-12 col-md-6">
                <a href="{{ route('pembimbing.siswa.index') }}"
                   class="btn btn-outline-primary w-100 p-3 text-start">
                    <i class="bi bi-people-fill fs-3 d-block mb-2"></i>
                    <strong>Daftar Siswa Bimbingan</strong>
                    <small class="d-block text-secondary">Lihat data dan perkembangan siswa</small>
                </a>
            </div>

            <div class="col-12 col-md-6">
                <a href="{{ route('pembimbing.siswa.index') }}"
                   class="btn btn-outline-warning w-100 p-3 text-start">
                    <i class="bi bi-journal-check fs-3 d-block mb-2"></i>
                    <strong>Periksa Jurnal</strong>
                    <small class="d-block text-secondary">Periksa jurnal dan berikan paraf</small>
                </a>
            </div>

        </div>
    </div>
</div>

@endsection

{{-- SCRIPT GRAFIK PEMBIMBING --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const attendanceCanvas = document.getElementById('pembimbingAttendanceChart');
    const journalCanvas = document.getElementById('pembimbingJournalChart');

    if (typeof Chart === 'undefined') {
        console.error('Chart.js belum berhasil dimuat.');
        return;
    }

    if (attendanceCanvas) {
        new Chart(attendanceCanvas, {
            type: 'line',
            data: {
                labels: @json($namaBulan),
                datasets: [{
                    label: 'Jumlah Kehadiran',
                    data: @json($dataKehadiran),
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.12)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#2563eb'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }

    if (journalCanvas) {
        new Chart(journalCanvas, {
            type: 'doughnut',
            data: {
                labels: ['Menunggu', 'Disetujui', 'Revisi'],
                datasets: [{
                    data: @json($statusJurnal),
                    backgroundColor: [
                        '#f59e0b',
                        '#10b981',
                        '#ef4444'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

});
</script>
@endpush

