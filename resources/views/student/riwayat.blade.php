
@extends('layouts.main')

@section('title', 'Riwayat Absensi')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h3 class="fw-bold">Riwayat Absensi</h3>
        <p class="text-muted">Lihat catatan kehadiran dan kepulangan PKL kamu.</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Daftar Kehadiran</h5>
                <span class="badge bg-primary">
                    {{ $attendances->total() }} Data
                </span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Masuk</th>
                            <th>Pulang</th>
                            <th>Status</th>
                            <th>Lokasi</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($attendances as $attendance)
                            <tr>
                                <td>
                                    {{ $attendances->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($attendance->date)->format('d-m-Y') }}
                                </td>

                                <td>{{ $attendance->check_in_time ?? '-' }}</td>

                                <td>{{ $attendance->check_out_time ?? '-' }}</td>

                                <td>
                                    @if ($attendance->status === 'hadir')
                                        <span class="badge bg-success">Hadir</span>
                                    @elseif ($attendance->status === 'izin')
                                        <span class="badge bg-warning text-dark">Izin</span>
                                    @elseif ($attendance->status === 'sakit')
                                        <span class="badge bg-info text-dark">Sakit</span>
                                    @else
                                        <span class="badge bg-danger">Alpha</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($attendance->is_outside_area)
                                        <span class="badge bg-danger">Di luar radius</span>
                                    @elseif ($attendance->check_in_time)
                                        <span class="badge bg-success">Dalam radius</span>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>{{ $attendance->note ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Belum ada riwayat absensi.
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
</div>
@endsection
