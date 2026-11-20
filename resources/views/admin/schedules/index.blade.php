
@extends('layouts.main')

@section('title', 'Jadwal PKL - PKL Connect')
@section('page-heading', 'Jadwal PKL')

@section('content')

@php
    $namaBulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
        4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September',
        10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    $bulanTerpilih = \Carbon\Carbon::createFromFormat('Y-m', $month);
    $totalJadwal = $schedules->total();
    $jadwalAktif = $schedules->getCollection()->where('status', 'aktif')->count();
    $jadwalLibur = $schedules->getCollection()->where('status', 'libur')->count();
@endphp

<div class="container-fluid px-0">

    {{-- HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Manajemen Jadwal PKL</h4>
            <p class="text-muted mb-0">
                Buat dan pantau jadwal kegiatan siswa PKL setiap bulan.
            </p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <span class="badge rounded-pill px-3 py-2"
                  style="background:#eaf1ff;color:#2563eb;">
                <i class="bi bi-calendar3 me-1"></i>
                {{ $namaBulan[$bulanTerpilih->month] }} {{ $bulanTerpilih->year }}
            </span>
        </div>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- STATISTIK --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="width:48px;height:48px;background:#eaf1ff;color:#2563eb;">
                        <i class="bi bi-calendar-week fs-4"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-1">Total Jadwal</p>
                        <h4 class="fw-bold mb-0">{{ $totalJadwal }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="width:48px;height:48px;background:#e8f7ee;color:#198754;">
                        <i class="bi bi-check-circle fs-4"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-1">Jadwal Aktif</p>
                        <h4 class="fw-bold mb-0">{{ $jadwalAktif }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="width:48px;height:48px;background:#fff3e6;color:#dc6b19;">
                        <i class="bi bi-calendar-x fs-4"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-1">Jadwal Libur</p>
                        <h4 class="fw-bold mb-0">{{ $jadwalLibur }}</h4>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- FILTER DAN GENERATE --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">

            <div class="row g-4 align-items-end">

                <div class="col-lg-6">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-funnel me-2" style="color:#2563eb;"></i>
                        Filter Jadwal
                    </h6>

                    <form action="{{ route('admin.schedule.index') }}"
                          method="GET"
                          class="d-flex flex-wrap gap-2">

                        <input type="month"
                               name="month"
                               class="form-control"
                               value="{{ $month }}"
                               style="max-width:220px;"
                               required>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-1"></i>
                            Tampilkan
                        </button>
                    </form>
                </div>

                <div class="col-lg-6">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-magic me-2" style="color:#2563eb;"></i>
                        Generate Jadwal Otomatis
                    </h6>

                    <form action="{{ route('admin.schedule.generate') }}"
                          method="POST"
                          class="d-flex flex-wrap gap-2"
                          onsubmit="return confirm('Generate jadwal untuk bulan yang dipilih? Jadwal yang sudah ada tidak akan ditimpa.')">

                        @csrf

                        <input type="month"
                               name="month"
                               class="form-control"
                               value="{{ $month }}"
                               style="max-width:220px;"
                               required>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-calendar-plus me-1"></i>
                            Generate
                        </button>
                    </form>
                </div>

            </div>

            <div class="alert alert-info border-0 mt-4 mb-0 small">
                <i class="bi bi-info-circle-fill me-2"></i>
                Jadwal otomatis dibuat pada hari kerja (Senin–Jumat), sesuai periode penempatan aktif.
                Jam default adalah 08.00–16.00. Jadwal yang sudah ada tidak ditimpa.
            </div>

        </div>
    </div>

    {{-- TABEL JADWAL --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 px-4 pt-4 pb-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <h6 class="fw-bold mb-1">Daftar Jadwal Siswa</h6>
                    <p class="text-muted small mb-0">
                        Jadwal bulan {{ $namaBulan[$bulanTerpilih->month] }}
                        {{ $bulanTerpilih->year }}
                    </p>
                </div>

                <span class="badge bg-light text-dark border px-3 py-2">
                    {{ $totalJadwal }} data
                </span>
            </div>
        </div>

        <div class="card-body p-0">

            @if($schedules->count() > 0)

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">

                        <thead style="background:#f8fafc;">
                            <tr>
                                <th class="px-4 py-3 text-muted small">TANGGAL</th>
                                <th class="py-3 text-muted small">SISWA</th>
                                <th class="py-3 text-muted small">PEMBIMBING</th>
                                <th class="py-3 text-muted small">LOKASI PKL</th>
                                <th class="py-3 text-muted small">JAM</th>
                                <th class="py-3 text-muted small">STATUS</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($schedules as $schedule)
                                <tr>

                                    <td class="px-4">
                                        <div class="fw-semibold">
                                            {{ \Carbon\Carbon::parse($schedule->date)->translatedFormat('d M Y') }}
                                        </div>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($schedule->date)->translatedFormat('l') }}
                                        </small>
                                    </td>

                                    <td>
                                        <div class="fw-semibold">
                                            {{ $schedule->placement->student->name ?? '-' }}
                                        </div>
                                    </td>

                                    <td>
                                        {{ $schedule->placement->pembimbing->name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $schedule->placement->location->name ?? '-' }}
                                    </td>

                                    <td>
                                        @if($schedule->status === 'libur')
                                            <span class="text-muted">-</span>
                                        @else
                                            <span class="fw-semibold">
                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                                                -
                                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($schedule->status === 'libur')
                                            <span class="badge rounded-pill px-3 py-2"
                                                  style="background:#fff0e6;color:#d35400;">
                                                <i class="bi bi-calendar-x me-1"></i>
                                                Libur
                                            </span>
                                        @else
                                            <span class="badge rounded-pill px-3 py-2"
                                                  style="background:#e8f7ee;color:#198754;">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Aktif
                                            </span>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

                <div class="p-4 border-top">
                    {{ $schedules->links() }}
                </div>

            @else

                <div class="text-center py-5 px-3">
                    <div class="mb-3">
                        <i class="bi bi-calendar2-x"
                           style="font-size:48px;color:#94a3b8;"></i>
                    </div>

                    <h6 class="fw-bold">Belum Ada Jadwal</h6>

                    <p class="text-muted small mb-3">
                        Belum ada jadwal untuk bulan ini.
                        Silakan gunakan tombol Generate Jadwal Otomatis.
                    </p>
                </div>

            @endif

        </div>
    </div>

</div>

@endsection
