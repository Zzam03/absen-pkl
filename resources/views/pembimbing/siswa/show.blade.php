
@extends('layouts.main')

@section('title', 'Detail Siswa - PKL Connect')
@section('page-heading', 'Detail Siswa')

@section('content')

{{-- HEADER SISWA --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <a href="{{ route('pembimbing.siswa.index') }}"
           class="text-decoration-none small">
            <i class="bi bi-arrow-left"></i> Kembali ke daftar siswa
        </a>

        <h4 class="fw-bold mt-2 mb-1">
            {{ $placement->student->name ?? 'Nama siswa' }}
        </h4>

        <p class="text-muted mb-0">
            Detail aktivitas dan perkembangan siswa PKL.
        </p>
    </div>

    <a href="{{ route('pembimbing.nilai.form', $placement) }}"
       class="btn btn-primary">
        <i class="bi bi-pencil-square me-1"></i> Form Penilaian
    </a>
</div>

{{-- INFORMASI PENEMPATAN --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-3">Informasi Siswa</h5>

        <div class="row g-3">
            <div class="col-md-4">
                <small class="text-muted">Nama Siswa</small>
                <div class="fw-semibold">{{ $placement->student->name ?? '-' }}</div>
            </div>

            <div class="col-md-4">
                <small class="text-muted">Email</small>
                <div class="fw-semibold">{{ $placement->student->email ?? '-' }}</div>
            </div>

            <div class="col-md-4">
                <small class="text-muted">Sekolah</small>
                <div class="fw-semibold">{{ $placement->student->school_name ?? '-' }}</div>
            </div>

            <div class="col-md-4">
                <small class="text-muted">Lokasi PKL</small>
                <div class="fw-semibold">{{ $placement->location->name ?? '-' }}</div>
            </div>

            <div class="col-md-4">
                <small class="text-muted">Tanggal Mulai</small>
                <div class="fw-semibold">
                    {{ $placement->start_date?->format('d-m-Y') ?? '-' }}
                </div>
            </div>

            <div class="col-md-4">
                <small class="text-muted">Tanggal Selesai</small>
                <div class="fw-semibold">
                    {{ $placement->end_date?->format('d-m-Y') ?? '-' }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- RINGKASAN NILAI --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-3">
                <small class="text-muted">Total Kehadiran</small>
                <h3 class="fw-bold mb-0">{{ $attendances->total() }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-3">
                <small class="text-muted">Total Jurnal</small>
                <h3 class="fw-bold mb-0">{{ $journals->total() }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-3">
                <small class="text-muted">Nilai Akhir</small>
                @if($score)
                    <h3 class="fw-bold text-success mb-0">
                        {{ number_format((float) $score->final_score, 2) }}
                    </h3>
                @else
                    <h3 class="fw-bold text-warning mb-0">Belum ada</h3>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- RIWAYAT ABSENSI --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-calendar-check text-primary me-2"></i>
                Riwayat Absensi
            </h5>
            <span class="badge bg-primary-subtle text-primary">
                {{ $attendances->total() }} Data
            </span>
        </div>

        @if($attendances->count())
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Status</th>
                            <th>Lokasi</th>
                            <th>Jarak</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($attendances as $attendance)
                            <tr>
                                <td>
                                    {{ $attendance->date?->format('d-m-Y') ?? '-' }}
                                </td>

                                <td>
                                    {{ $attendance->check_in_time
                                        ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i')
                                        : '-' }}
                                </td>

                                <td>
                                    {{ $attendance->check_out_time
                                        ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i')
                                        : '-' }}
                                </td>

                                <td>
                                    @if($attendance->status === 'hadir')
                                        <span class="badge bg-success">Hadir</span>
                                    @elseif($attendance->status === 'izin')
                                        <span class="badge bg-info text-dark">Izin</span>
                                    @elseif($attendance->status === 'sakit')
                                        <span class="badge bg-warning text-dark">Sakit</span>
                                    @else
                                        <span class="badge bg-secondary">
                                            {{ ucfirst($attendance->status ?? '-') }}
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if($attendance->is_outside_area)
                                        <span class="badge bg-danger">Di luar area</span>
                                    @else
                                        <span class="badge bg-success">Dalam area</span>
                                    @endif
                                </td>

                                <td>
                                    {{ $attendance->check_in_distance !== null
                                        ? number_format((float) $attendance->check_in_distance) . ' m'
                                        : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $attendances->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="text-center text-muted py-4">
                <i class="bi bi-calendar-x fs-1"></i>
                <p class="mb-0 mt-2">Belum ada riwayat absensi.</p>
            </div>
        @endif
    </div>
</div>

{{-- JURNAL SISWA --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-journal-text text-primary me-2"></i>
                Jurnal Kegiatan Siswa
            </h5>

            <span class="badge bg-primary-subtle text-primary">
                {{ $journals->total() }} Jurnal
            </span>
        </div>

        @if($journals->count())
            <div class="d-flex flex-column gap-3">

                @foreach($journals as $journal)
                    <div class="border rounded-4 p-3">

                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                            <div>
                                <div class="fw-bold">
                                    {{ $journal->date?->format('d F Y') ?? '-' }}
                                </div>
                                <small class="text-muted">
                                    Jurnal #{{ $journal->id }}
                                </small>
                            </div>

                            @if($journal->status === 'pending')
                                <span class="badge bg-warning text-dark">Menunggu Paraf</span>
                            @elseif($journal->status === 'approved')
                                <span class="badge bg-success">Disetujui</span>
                            @elseif($journal->status === 'revision')
                                <span class="badge bg-danger">Perlu Revisi</span>
                            @else
                                <span class="badge bg-secondary">
                                    {{ ucfirst($journal->status) }}
                                </span>
                            @endif
                        </div>

                        <p class="mb-3" style="white-space: pre-line;">{{ $journal->activity }}</p>

                        @if($journal->photo)
                            <div class="mb-3">
                                <a href="{{ asset('storage/' . $journal->photo) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-image me-1"></i> Lihat Foto Kegiatan
                                </a>
                            </div>
                        @endif

                        @if($journal->revision_note)
                            <div class="alert alert-danger py-2 mb-3">
                                <strong>Catatan Revisi:</strong>
                                <div>{{ $journal->revision_note }}</div>
                            </div>
                        @endif

                        {{-- TOMBOL PARAF DAN REVISI --}}
                        @if($journal->status === 'pending')

                            <div class="border-top pt-3 mt-2">
                                <div class="d-flex flex-wrap gap-2 mb-3">

                                    <form action="{{ route('pembimbing.jurnal.paraf', $journal) }}"
                                          method="POST"
                                          onsubmit="return confirm('Apakah kamu yakin ingin memberikan paraf pada jurnal ini?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Setujui & Paraf
                                        </button>
                                    </form>

                                </div>

                                <form action="{{ route('pembimbing.jurnal.revisi', $journal) }}"
                                      method="POST">
                                    @csrf

                                    <label class="form-label fw-semibold">
                                        Catatan jika jurnal perlu direvisi
                                    </label>

                                    <textarea name="revision_note"
                                              class="form-control mb-2"
                                              rows="2"
                                              maxlength="500"
                                              required
                                              placeholder="Tuliskan bagian yang harus diperbaiki siswa..."></textarea>

                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-arrow-return-left me-1"></i>
                                        Kembalikan untuk Revisi
                                    </button>
                                </form>
                            </div>

                        @elseif($journal->status === 'approved')
                            <div class="small text-success border-top pt-2 mt-2">
                                <i class="bi bi-patch-check-fill me-1"></i>
                                Jurnal sudah diparaf pembimbing.
                                @if($journal->pembimbing_signed_at)
                                    {{ $journal->pembimbing_signed_at->format('d-m-Y H:i') }}
                                @endif
                            </div>
                        @endif

                    </div>
                @endforeach

            </div>

            <div class="mt-3">
                {{ $journals->links('pagination::bootstrap-5') }}
            </div>

        @else
            <div class="text-center text-muted py-4">
                <i class="bi bi-journal-x fs-1"></i>
                <p class="mb-0 mt-2">Siswa belum mengirim jurnal kegiatan.</p>
            </div>
        @endif

    </div>
</div>

{{-- NILAI --}}
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="fw-bold mb-1">Penilaian PKL</h5>

                @if($score)
                    <p class="text-muted mb-0">
                        Nilai akhir:
                        <strong class="text-success">
                            {{ number_format((float) $score->final_score, 2) }}
                        </strong>
                    </p>
                @else
                    <p class="text-muted mb-0">Siswa belum memiliki nilai akhir.</p>
                @endif
            </div>

            <a href="{{ route('pembimbing.nilai.form', $placement) }}"
               class="btn btn-primary">
                <i class="bi bi-pencil-square me-1"></i>
                {{ $score ? 'Ubah Penilaian' : 'Beri Penilaian' }}
            </a>
        </div>
    </div>
</div>

@endsection
