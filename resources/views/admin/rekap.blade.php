
@extends('layouts.main')

@section('title', 'Rekap Absensi')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Rekap Absensi</h4>
            <p class="text-muted mb-0">Pantau dan unduh laporan kehadiran siswa PKL.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.rekap.export', array_merge(request()->query(), ['format' => 'xlsx'])) }}"
               class="btn btn-success">
                <i class="bi bi-file-earmark-excel me-1"></i> Excel
            </a>
            <a href="{{ route('admin.rekap.export', array_merge(request()->query(), ['format' => 'pdf'])) }}"
               class="btn btn-danger">
                <i class="bi bi-file-earmark-pdf me-1"></i> PDF
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-funnel me-2"></i>Filter Data
            </h6>

            <form action="{{ route('admin.rekap') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" name="dari" class="form-control"
                               value="{{ request('dari') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" name="sampai" class="form-control"
                               value="{{ request('sampai') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Nama Siswa</label>
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

                    <div class="col-md-3">
                        <label class="form-label">Status Kehadiran</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                            <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="alpha" {{ request('status') == 'alpha' ? 'selected' : '' }}>Alpha</option>
                        </select>
                    </div>

                    <div class="col-12 d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                   name="outside" value="1" id="outside"
                                   {{ request()->boolean('outside') ? 'checked' : '' }}>
                            <label class="form-check-label" for="outside">
                                Hanya tampilkan absensi di luar area
                            </label>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.rekap') }}" class="btn btn-light border">
                                Reset
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i> Terapkan Filter
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-bold mb-1">Data Kehadiran Siswa</h6>
                    <small class="text-muted">
                        Total {{ $attendances->total() }} data absensi
                    </small>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Tanggal</th>
                            <th>Siswa</th>
                            <th>Lokasi PKL</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Status</th>
                            <th>Area</th>
                            <th>Jarak</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                            <tr>
                                <td>{{ $attendances->firstItem() + $loop->index }}</td>
                                <td>{{ $attendance->date?->format('d/m/Y') ?? '-' }}</td>
                                <td>
                                    <div class="fw-semibold">
                                        {{ $attendance->student->name ?? '-' }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $attendance->student->school_name ?? '-' }}
                                    </small>
                                </td>
                                <td>{{ $attendance->placement->location->name ?? '-' }}</td>
                                <td>{{ $attendance->check_in_time ? substr($attendance->check_in_time, 0, 5) : '-' }}</td>
                                <td>{{ $attendance->check_out_time ? substr($attendance->check_out_time, 0, 5) : '-' }}</td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'hadir' => 'success',
                                            'izin' => 'info',
                                            'sakit' => 'warning',
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
                                <td>{{ $attendance->check_in_distance !== null ? $attendance->check_in_distance . ' m' : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <i class="bi bi-clipboard-data display-5 text-muted"></i>
                                    <h6 class="fw-bold mt-3">Belum Ada Data Absensi</h6>
                                    <p class="text-muted mb-0">
                                        Data kehadiran siswa akan muncul di sini setelah siswa melakukan absensi.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($attendances->hasPages())
                <div class="mt-3">
                    {{ $attendances->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
