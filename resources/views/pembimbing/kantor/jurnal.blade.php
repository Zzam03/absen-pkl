@extends('layouts.main')

@section('title', 'Jurnal Harian - PKL Connect')
@section('page-heading', 'Jurnal Harian')

@section('content')

{{-- HEADER --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1">Jurnal Harian Siswa</h4>
        <p class="text-muted mb-0">
            Pantau aktivitas dan perkembangan jurnal harian siswa bimbingan Anda.
        </p>
    </div>

    <span class="badge bg-primary-subtle text-primary px-3 py-2">
        <i class="bi bi-journal-text me-1"></i>
        Pembimbing Kantor
    </span>
</div>

{{-- RINGKASAN --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 bg-primary-subtle text-primary p-3">
                    <i class="bi bi-journal-text fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Data Jurnal</div>
                    <h4 class="fw-bold mb-0">{{ $journals->total() }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 bg-warning-subtle text-warning p-3">
                    <i class="bi bi-hourglass-split fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Menunggu Paraf</div>
                    <h4 class="fw-bold mb-0">
                        {{ $journals->where('status', 'pending')->count() }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 bg-success-subtle text-success p-3">
                    <i class="bi bi-check-circle fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Sudah Disetujui</div>
                    <h4 class="fw-bold mb-0">
                        {{ $journals->where('status', 'approved')->count() }}
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
            <h6 class="fw-bold mb-0">Filter Jurnal</h6>
        </div>

        <form action="{{ route('pembimbing.kantor.jurnal') }}" method="GET">
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

                <div class="col-12 col-md-6 col-lg-3">
                    <label class="form-label small fw-semibold">Status Jurnal</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                            Menunggu Paraf
                        </option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                            Disetujui
                        </option>
                        <option value="revision" {{ request('status') == 'revision' ? 'selected' : '' }}>
                            Perlu Revisi
                        </option>
                    </select>
                </div>

                <div class="col-12 col-md-6 col-lg-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-search me-1"></i>
                        Filter
                    </button>

                    <a href="{{ route('pembimbing.kantor.jurnal') }}"
                       class="btn btn-light border"
                       title="Reset filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- TABEL JURNAL --}}
<div class="card border-0 shadow-sm">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <h6 class="fw-bold mb-1">Daftar Jurnal Harian</h6>
                <p class="text-muted small mb-0">
                    Jurnal dari siswa yang berada dalam bimbingan Anda.
                </p>
            </div>

            <span class="text-muted small">
                {{ $journals->firstItem() ?? 0 }}–{{ $journals->lastItem() ?? 0 }}
                dari {{ $journals->total() }} data
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Siswa</th>
                        <th>Aktivitas</th>
                        <th>Dokumentasi</th>
                        <th>Status</th>
                        <th>Diparaf Oleh</th>
                        <th>Catatan Revisi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($journals as $index => $journal)
                        <tr>
                            <td>{{ $journals->firstItem() + $index }}</td>

                            <td class="text-nowrap">
                                {{ $journal->date?->format('d/m/Y') ?? '-' }}
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $journal->student->name ?? '-' }}
                                </div>
                            </td>

                            <td style="min-width: 220px; max-width: 320px;">
                                <div style="white-space: normal;">
                                    {{ $journal->activity }}
                                </div>
                            </td>

                            <td>
                                @if($journal->photo)
                                    <a href="{{ asset('storage/' . $journal->photo) }}"
                                       target="_blank"
                                       class="btn btn-sm btn-light border">
                                        <i class="bi bi-image me-1"></i>
                                        Lihat Foto
                                    </a>
                                @else
                                    <span class="text-muted small">Tidak ada foto</span>
                                @endif
                            </td>

                            <td>
                                @if($journal->status === 'approved')
                                    <span class="badge bg-success-subtle text-success">
                                        Disetujui
                                    </span>
                                @elseif($journal->status === 'pending')
                                    <span class="badge bg-warning-subtle text-warning">
                                        Menunggu Paraf
                                    </span>
                                @elseif($journal->status === 'revision')
                                    <span class="badge bg-danger-subtle text-danger">
                                        Perlu Revisi
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">
                                        {{ ucfirst($journal->status ?? '-') }}
                                    </span>
                                @endif
                            </td>

                            <td>
                                {{ $journal->signer->name ?? '-' }}
                            </td>

                            <td style="min-width: 180px; max-width: 250px;">
                                <div class="small text-muted" style="white-space: normal;">
                                    {{ $journal->revision_note ?: '-' }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="mb-3">
                                    <i class="bi bi-journal-x text-muted"
                                       style="font-size: 2.5rem;"></i>
                                </div>
                                <h6 class="fw-bold">Belum Ada Jurnal</h6>
                                <p class="text-muted small mb-0">
                                    Jurnal harian siswa bimbingan akan muncul di halaman ini.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $journals->links() }}
        </div>

    </div>
</div>

@endsection
