@extends('layouts.main')

@section('title', 'Riwayat Absensi - PKL Connect')
@section('page-heading', 'Riwayat Absensi')

@section('content')

{{-- HEADER --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1">Riwayat Absensi Siswa</h4>
        <p class="text-muted mb-0">
            Pantau kehadiran seluruh siswa yang menjadi bimbingan Anda.
        </p>
    </div>

    <div class="d-flex align-items-center gap-2">
        <span class="badge bg-primary-subtle text-primary px-3 py-2">
            <i class="bi bi-clipboard-check me-1"></i>
            Pembimbing Kantor
        </span>
    </div>
</div>

{{-- RINGKASAN --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 bg-primary-subtle text-primary p-3">
                    <i class="bi bi-calendar-check fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Data Absensi</div>
                    <h4 class="fw-bold mb-0">
                        {{ $attendances->total() }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 bg-success-subtle text-success p-3">
                    <i class="bi bi-people fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Siswa Bimbingan</div>
                    <h4 class="fw-bold mb-0">
                        {{ $students->count() }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 bg-info-subtle text-info p-3">
                    <i class="bi bi-funnel fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Data Ditampilkan</div>
                    <h4 class="fw-bold mb-0">
                        {{ $attendances->count() }}
                    </h4>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- FILTER --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-funnel text-primary"></i>
            <h6 class="fw-bold mb-0">Filter Riwayat Absensi</h6>
        </div>

        <form action="{{ route('pembimbing.kantor.absensi') }}" method="GET">
            <div class="row g-3">

                <div class="col-12 col-md-6 col-lg-3">
                    <label class="form-label small fw-semibold">Nama Siswa</label>
                    <select name="student_id" class="form-select">
                        <option value="">Semua Siswa</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}"
                                {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-6 col-lg-2">
                    <label class="form-label small fw-semibold">Dari Tanggal</label>
                    <input type="date" name="dari" class="form-control"
                        value="{{ request('dari') }}">
                </div>

                <div class="col-12 col-md-6 col-lg-2">
                    <label class="form-label small fw-semibold">Sampai Tanggal</label>
                    <input type="date" name="sampai" class="form-control"
                        value="{{ request('sampai') }}">
                </div>

                <div class="col-12 col-md-6 col-lg-2">
                    <label class="form-label small fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                        <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="alpha" {{ request('status') == 'alpha' ? 'selected' : '' }}>Alpha</option>
                    </select>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <label class="form-label small fw-semibold">Lokasi Absensi</label>
                    <select name="outside" class="form-select">
                        <option value="">Semua Lokasi</option>
                        <option value="1" {{ request('outside') == '1' ? 'selected' : '' }}>
                            Di Luar Area
                        </option>
                    </select>
                </div>

                <div class="col-12 d-flex flex-wrap gap-2 justify-content-end">
                    <a href="{{ route('pembimbing.kantor.absensi') }}"
                       class="btn btn-light border">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                        Reset
                    </a>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>
                        Terapkan Filter
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- TABEL ABSENSI --}}
<div class="card border-0 shadow-sm">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <h6 class="fw-bold mb-1">Data Riwayat Absensi</h6>
                <p class="text-muted small mb-0">
                    Menampilkan riwayat absensi siswa bimbingan Anda.
                </p>
            </div>

            <span class="text-muted small">
                {{ $attendances->firstItem() ?? 0 }}–{{ $attendances->lastItem() ?? 0 }}
                dari {{ $attendances->total() }} data
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Siswa</th>
                        <th>Lokasi PKL</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Status</th>
                        <th>Area</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($attendances as $index => $attendance)
                        <tr>
                            <td>{{ $attendances->firstItem() + $index }}</td>

                            <td class="text-nowrap">
                                {{ $attendance->date?->format('d/m/Y') ?? '-' }}
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $attendance->student->name ?? '-' }}
                                </div>
                            </td>

                            <td>
                                {{ $attendance->placement->location->name ?? '-' }}
                            </td>

                            <td class="text-nowrap">
                                {{ $attendance->check_in_time ?? '-' }}
                            </td>

                            <td class="text-nowrap">
                                {{ $attendance->check_out_time ?? '-' }}
                            </td>

                            <td>
                                @php
                                    $statusClass = [
                                        'hadir' => 'success',
                                        'izin' => 'info',
                                        'sakit' => 'warning',
                                        'alpha' => 'danger',
                                    ][$attendance->status] ?? 'secondary';
                                @endphp

                                <span class="badge bg-{{ $statusClass }}-subtle text-{{ $statusClass }}">
                                    {{ ucfirst($attendance->status ?? '-') }}
                                </span>
                            </td>

                            <td>
                                @if($attendance->is_outside_area)
                                    <span class="badge bg-danger-subtle text-danger">
                                        Di Luar Area
                                    </span>
                                @else
                                    <span class="badge bg-success-subtle text-success">
                                        Dalam Area
                                    </span>
                                @endif
                            </td>

                            <td>
                                {{ $attendance->note ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="mb-3">
                                    <i class="bi bi-calendar-x text-muted"
                                       style="font-size: 2.5rem;"></i>
                                </div>
                                <h6 class="fw-bold">Belum Ada Data Absensi</h6>
                                <p class="text-muted small mb-0">
                                    Riwayat absensi siswa bimbingan akan muncul di halaman ini.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $attendances->links() }}
        </div>

    </div>
</div>

@endsection
