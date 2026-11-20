@extends('layouts.main')

@section('title', 'Dashboard Admin')
@section('page-heading', 'Dashboard Admin')

@section('content')

<div class="container-fluid px-0">

    {{-- SAPAAN --}}
    <div class="mb-4">
        <h4 class="fw-bold text-dark mb-1">
            Selamat Datang, {{ auth()->user()->name }}!
        </h4>
        <p class="text-secondary mb-0">
            Berikut ringkasan aktivitas PKL Connect hari ini.
        </p>
    </div>

    {{-- KARTU STATISTIK --}}
    <div class="row g-3 mb-4">

        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-secondary small">Total Siswa</span>
                        <i class="bi bi-people-fill text-primary fs-4"></i>
                    </div>
                    <h3 class="fw-bold mb-0" id="statSiswa">-</h3>
                    <small class="text-secondary">Siswa terdaftar</small>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-secondary small">Hadir Hari Ini</span>
                        <i class="bi bi-check-circle-fill text-success fs-4"></i>
                    </div>
                    <h3 class="fw-bold mb-0" id="statHadir">-</h3>
                    <small class="text-secondary">Kehadiran hari ini</small>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-secondary small">Penempatan</span>
                        <i class="bi bi-building-fill text-warning fs-4"></i>
                    </div>
                    <h3 class="fw-bold mb-0" id="statPenempatan">-</h3>
                    <small class="text-secondary">Penempatan aktif</small>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-secondary small">Pembimbing</span>
                        <i class="bi bi-person-badge-fill text-info fs-4"></i>
                    </div>
                    <h3 class="fw-bold mb-0" id="statPembimbing">-</h3>
                    <small class="text-secondary">Total pembimbing</small>
                </div>
            </div>
        </div>

    </div>

    {{-- GRAFIK --}}
    <div class="row g-3 mb-4">

        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-1">Tren Kehadiran Siswa</h5>
                    <p class="text-secondary small mb-4">
                        Ringkasan kehadiran berdasarkan bulan.
                    </p>

                    <div style="height: 300px; position: relative;">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-1">Ringkasan Status</h5>
                    <p class="text-secondary small mb-4">
                        Gambaran status kehadiran.
                    </p>

                    <div style="height: 300px; position: relative;">
                        <canvas id="doughnutChart"></canvas>
                    </div>

                    <p class="text-secondary small text-center mt-3 mb-0">
                        Data ilustrasi sementara
                    </p>
                </div>
            </div>
        </div>

    </div>

    {{-- MENU CEPAT --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <h5 class="fw-bold mb-3">Menu Pengelolaan</h5>

            <div class="row g-3">

                <div class="col-6 col-md-3">
                    <a href="{{ route('admin.users.index') }}"
                       class="btn btn-light border w-100 py-3 text-start">
                        <i class="bi bi-people-fill text-primary fs-4 d-block mb-2"></i>
                        <span class="fw-semibold">Data Pengguna</span>
                    </a>
                </div>

                <div class="col-6 col-md-3">
                    <a href="{{ route('admin.locations.index') }}"
                       class="btn btn-light border w-100 py-3 text-start">
                        <i class="bi bi-geo-alt-fill text-danger fs-4 d-block mb-2"></i>
                        <span class="fw-semibold">Lokasi PKL</span>
                    </a>
                </div>

                <div class="col-6 col-md-3">
                    <a href="{{ route('admin.placements.index') }}"
                       class="btn btn-light border w-100 py-3 text-start">
                        <i class="bi bi-building-fill text-warning fs-4 d-block mb-2"></i>
                        <span class="fw-semibold">Penempatan PKL</span>
                    </a>
                </div>

                <div class="col-6 col-md-3">
                    <a href="{{ route('admin.rekap') }}"
                       class="btn btn-light border w-100 py-3 text-start">
                        <i class="bi bi-clipboard-data-fill text-success fs-4 d-block mb-2"></i>
                        <span class="fw-semibold">Rekap Absensi</span>
                    </a>
                </div>

            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // AMBIL DATA STATISTIK
    fetch('/api/dashboard/summary')
        .then(response => {
            if (!response.ok) {
                throw new Error('Gagal mengambil data statistik');
            }
            return response.json();
        })
        .then(data => {
            document.getElementById('statSiswa').textContent = data.total_siswa ?? 0;
            document.getElementById('statHadir').textContent = data.hadir_hari_ini ?? 0;
            document.getElementById('statPenempatan').textContent = data.penempatan_aktif ?? 0;
            document.getElementById('statPembimbing').textContent = data.total_pembimbing ?? 0;
        })
        .catch(error => {
            console.error('Statistik:', error);
        });

    // AMBIL DATA GRAFIK
    fetch('/api/dashboard/chart-data')
        .then(response => {
            if (!response.ok) {
                throw new Error('Gagal mengambil data grafik');
            }
            return response.json();
        })
        .then(data => {

            const monthNames = [
                'Jan', 'Feb', 'Mar', 'Apr',
                'Mei', 'Jun', 'Jul', 'Ags',
                'Sep', 'Okt', 'Nov', 'Des'
            ];

            let chartData = Array(12).fill(0);

            data.forEach(item => {
                if (item.month >= 1 && item.month <= 12) {
                    chartData[item.month - 1] = item.total;
                }
            });

            // GRAFIK GARIS
            const lineCanvas = document.getElementById('lineChart');

            if (lineCanvas) {
                new Chart(lineCanvas, {
                    type: 'line',
                    data: {
                        labels: monthNames,
                        datasets: [{
                            label: 'Siswa Hadir',
                            data: chartData,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4
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
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // GRAFIK DONAT
            const doughnutCanvas = document.getElementById('doughnutChart');

            if (doughnutCanvas) {
                new Chart(doughnutCanvas, {
                    type: 'doughnut',
                    data: {
                        labels: ['Hadir', 'Izin', 'Sakit', 'Alfa'],
                        datasets: [{
                            data: [105, 10, 5, 8],
                            backgroundColor: [
                                '#10b981',
                                '#f59e0b',
                                '#3b82f6',
                                '#ef4444'
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%'
                    }
                });
            }

        })
        .catch(error => {
            console.error('Grafik:', error);
        });

});
</script>
@endpush
