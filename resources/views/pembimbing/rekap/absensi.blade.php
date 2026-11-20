@extends('layouts.main')

@section('title', 'Rekap Absensi Sekolah - PKL Connect')
@section('page-heading', 'Rekap Absensi Sekolah')

@section('content')

@php
    $total = $attendances->total();
    $hadir = $attendances->where('status', 'hadir')->count();
    $izin = $attendances->where('status', 'izin')->count();
    $sakit = $attendances->where('status', 'sakit')->count();
    $alpha = $attendances->where('status', 'alpha')->count();
@endphp

<div class="mb-4">
    <p class="text-muted mb-0">
        Pantau riwayat kehadiran seluruh siswa dari {{ auth()->user()->school_name }}.
    </p>
</div>

{{-- RINGKASAN --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small mb-2">Total Data</div>
                <h3 class="fw-bold mb-0">{{ $total }}</h3>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small mb-2">Hadir</div>
                <h3 class="fw-bold text-success mb-0">{{ $hadir }}</h3>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small mb-2">Izin / Sakit</div>
                <h3 class="fw-bold text-warning mb-0">{{ $izin + $sakit }}</h3>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small mb-2">Alpha</div>
                <h3 class="fw-bold text-danger mb-0">{{ $alpha }}</h3>
            </div>
        </div>
    </div>
</div>

{{-- FILTER --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('pembimbing.rekap.absensi') }}" method="GET">
            <div class="row g-3 align-items-end">

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Nama Siswa</label>
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

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Dari Tanggal</label>
                    <input type="date" name="dari" class="form-control"
                        value="{{ request('dari') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Sampai Tanggal</label>
                    <input type="date" name="sampai" class="form-control"
                        value="{{ request('sampai') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        @foreach(['hadir' => 'Hadir', 'izin' => 'Izin', 'sakit' => 'Sakit', 'alpha' => 'Alpha'] as $value => $label)
                            <option value="{{ $value }}"
                                {{ request('status') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                </div>

                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"
                            name="outside" value="1" id="outside"
                            {{ request()->boolean('outside') ? 'checked' : '' }}>
                        <label class="form-check-label" for="outside">
                            Hanya tampilkan absensi di luar area
                        </label>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- TABEL ABSENSI --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <div class="fw-bold">Riwayat Absensi Siswa</div>
        <small class="text-muted">Data absensi siswa {{ auth()->user()->school_name }}</small>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Tanggal</th>
                        <th>Nama Siswa</th>
                        <th>Lokasi PKL</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Status</th>
                        <th>Area</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($attendances as $attendance)
                        <tr>
                            <td class="ps-3 text-nowrap">
                                {{ $attendance->date?->format('d/m/Y') ?? '-' }}
                            </td>

                            <td class="fw-semibold">
                                {{ $attendance->student->name ?? '-' }}
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
                                        'izin' => 'warning',
                                        'sakit' => 'info',
                                        'alpha' => 'danger',
                                    ][$attendance->status] ?? 'secondary';
                                @endphp

                                <span class="badge bg-{{ $statusClass }}">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            </td>

                            <td>
                                @if($attendance->is_outside_area)
                                    <span class="badge bg-danger">Di luar area</span>
                                @else
                                    <span class="badge bg-success">Sesuai area</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-calendar-x fs-2 text-muted"></i>
                                <p class="text-muted mt-2 mb-0">
                                    Belum ada data absensi.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($attendances->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $attendances->links() }}
        </div>
    @endif
</div>

@endsection
