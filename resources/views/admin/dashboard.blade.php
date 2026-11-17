@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto" data-aos="fade-up">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Dashboard Admin</h1>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-4 rounded-xl shadow-sm border-l-4 border-blue-500">
            <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Total Siswa</p>
            <h3 class="text-2xl font-bold text-gray-800 mt-1" id="statSiswa">-</h3>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border-l-4 border-green-500">
            <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Hadir Hari Ini</p>
            <h3 class="text-2xl font-bold text-gray-800 mt-1" id="statHadir">-</h3>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border-l-4 border-orange-500">
            <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Penempatan</p>
            <h3 class="text-2xl font-bold text-gray-800 mt-1" id="statPenempatan">-</h3>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border-l-4 border-purple-500">
            <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Pembimbing</p>
            <h3 class="text-2xl font-bold text-gray-800 mt-1" id="statPembimbing">-</h3>
        </div>
    </div>

    <!-- Grafik -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-700 mb-4">Tren Kehadiran Siswa</h3>
            <div class="h-64">
                <canvas id="lineChart"></canvas>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-700 mb-4">Distribusi Status Absensi</h3>
            <div class="h-64 flex justify-center">
                <canvas id="doughnutChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 untuk notifikasi -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Fetch Summary Data
    fetch('/api/dashboard/summary')
        .then(res => res.json())
        .then(data => {
            document.getElementById('statSiswa').innerText = data.total_siswa;
            document.getElementById('statHadir').innerText = data.hadir_hari_ini;
            document.getElementById('statPenempatan').innerText = data.penempatan_aktif;
            document.getElementById('statPembimbing').innerText = data.total_pembimbing;
        });

    // 2. Fetch Chart Data
    fetch('/api/dashboard/chart-data')
        .then(res => res.json())
        .then(data => {
            // Siapkan label bulan (Jan-Des)
            const monthNames = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"];
            let labels = [];
            let chartData = [];
            
            // Inisialisasi array 12 bulan dengan 0
            for(let i = 1; i <= 12; i++) {
                labels.push(monthNames[i-1]);
                chartData.push(0);
            }

            // Isi data dari API
            data.forEach(item => {
                chartData[item.month - 1] = item.total;
            });

            // Grafik Garis
            const ctxLine = document.getElementById('lineChart').getContext('2d');
            new Chart(ctxLine, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Siswa Hadir',
                        data: chartData,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: { 
                    responsive: true, 
                    maintainAspectRatio: false,
                    animation: { duration: 1500, easing: 'easeOutQuart' },
                    plugins: { legend: { display: false } }
                }
            });

            // Grafik Donat (Data Statis Sementara, bisa disesuaikan backend)
            const ctxDoughnut = document.getElementById('doughnutChart').getContext('2d');
            new Chart(ctxDoughnut, {
                type: 'doughnut',
                data: {
                    labels: ['Hadir', 'Izin', 'Sakit', 'Alfa'],
                    datasets: [{ 
                        data: [105, 10, 5, 8], 
                        backgroundColor: ['#10b981', '#f59e0b', '#3b82f6', '#ef4444'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    animation: { animateScale: true }
                }
            });
        });
});
</script>
@endsection