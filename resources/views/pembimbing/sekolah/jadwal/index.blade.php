@extends('layouts.main')

@section('title', 'Jadwal Siswa - PKL Connect')
@section('page-heading', 'Jadwal Siswa PKL')

@section('content')

<div class="card border-0 shadow-sm" style="border-radius:16px;">
    <div class="card-body p-4">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h5 class="fw-bold mb-1" style="color:#14213d;">
                    <i class="bi bi-calendar-week me-2 text-primary"></i>
                    Jadwal Siswa Bimbingan
                </h5>
                <p class="text-muted small mb-0">
                    Jadwal PKL siswa dari sekolah yang kamu bimbing.
                </p>
            </div>

            <form method="GET" action="{{ route('pembimbing.sekolah.jadwal.index') }}"
                  class="d-flex gap-2">
                <input type="month"
                       name="month"
                       value="{{ $month }}"
                       class="form-control"
                       style="max-width:190px;"
                       required>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-filter me-1"></i>
                    Filter
                </button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th class="py-3">Tanggal</th>
                        <th>Siswa</th>
                        <th>Lokasi PKL</th>
                        <th>Pembimbing Kantor</th>
                        <th>Jam</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($schedules as $schedule)
                        <tr>
                            <td class="fw-semibold">
                                {{ $schedule->date->translatedFormat('d M Y') }}
                            </td>

                            <td>
                                <div class="fw-semibold" style="color:#14213d;">
                                    {{ $schedule->placement->student->name ?? '-' }}
                                </div>
                            </td>

                            <td>
                                {{ $schedule->placement->location->name ?? '-' }}
                            </td>

                            <td>
                                {{ $schedule->placement->pembimbing->name ?? '-' }}
                            </td>

                            <td>
                                @if($schedule->status === 'libur')
                                    <span class="text-muted">-</span>
                                @else
                                    {{ substr($schedule->start_time, 0, 5) }}
                                    -
                                    {{ substr($schedule->end_time, 0, 5) }}
                                @endif
                            </td>

                            <td>
                                @if($schedule->status === 'libur')
                                    <span class="badge rounded-pill px-3 py-2"
                                          style="background:#fef2f2;color:#dc2626;">
                                        Libur
                                    </span>
                                @else
                                    <span class="badge rounded-pill px-3 py-2"
                                          style="background:#ecfdf5;color:#047857;">
                                        Aktif
                                    </span>
                                @endif
                            </td>

                            <td>
                                {{ $schedule->activity ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-calendar-x fs-1 text-muted"></i>
                                <h6 class="fw-bold mt-3" style="color:#14213d;">
                                    Belum Ada Jadwal
                                </h6>
                                <p class="text-muted small mb-0">
                                    Belum ada jadwal siswa untuk bulan yang dipilih.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $schedules->links() }}
        </div>

    </div>
</div>

@endsection
