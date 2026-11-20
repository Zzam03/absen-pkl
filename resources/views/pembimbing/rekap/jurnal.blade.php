@extends('layouts.main')

@section('title', 'Rekap Jurnal Sekolah - PKL Connect')
@section('page-heading', 'Rekap Jurnal Sekolah')

@section('content')

<div class="mb-4">
    <p class="text-muted mb-0">
        Pantau seluruh aktivitas dan perkembangan jurnal siswa
        {{ auth()->user()->school_name }}.
    </p>
</div>

{{-- RINGKASAN --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small mb-2">Total Jurnal</div>
                <h3 class="fw-bold mb-0">{{ $journals->total() }}</h3>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small mb-2">Menunggu Paraf</div>
                <h3 class="fw-bold text-warning mb-0">
                    {{ $journals->where('status', 'pending')->count() }}
                </h3>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small mb-2">Disetujui</div>
                <h3 class="fw-bold text-success mb-0">
                    {{ $journals->where('status', 'approved')->count() }}
                </h3>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small mb-2">Perlu Revisi</div>
                <h3 class="fw-bold text-danger mb-0">
                    {{ $journals->where('status', 'revision')->count() }}
                </h3>
            </div>
        </div>
    </div>
</div>

{{-- FILTER --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('pembimbing.rekap.jurnal') }}" method="GET">
            <div class="row g-3 align-items-end">

                <div class="col-md-3">
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

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Dari Tanggal</label>
                    <input type="date" name="dari" class="form-control"
                        value="{{ request('dari') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Sampai Tanggal</label>
                    <input type="date" name="sampai" class="form-control"
                        value="{{ request('sampai') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Status</label>
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

                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i>
                    </button>
                </div>

                <div class="col-12">
                    <a href="{{ route('pembimbing.rekap.jurnal') }}"
                       class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                        Reset Filter
                    </a>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- DAFTAR JURNAL --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <div class="fw-bold">Daftar Jurnal Harian Siswa</div>
        <small class="text-muted">
            Riwayat kegiatan PKL siswa {{ auth()->user()->school_name }}
        </small>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Tanggal</th>
                        <th>Nama Siswa</th>
                        <th>Aktivitas</th>
                        <th>Foto</th>
                        <th>Status</th>
                        <th>Paraf</th>
                        <th>Catatan Revisi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($journals as $journal)
                        <tr>
                            <td class="ps-3 text-nowrap">
                                {{ $journal->date?->format('d/m/Y') ?? '-' }}
                            </td>

                            <td class="fw-semibold">
                                {{ $journal->student->name ?? '-' }}
                            </td>

                            <td style="min-width: 220px; max-width: 350px;">
                                <div style="white-space: normal;">
                                    {{ $journal->activity }}
                                </div>
                            </td>

                            <td>
                                @if($journal->photo)
                                    <a href="{{ asset('storage/' . $journal->photo) }}"
                                       target="_blank"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-image"></i> Lihat
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td>
                                @php
                                    $statusClass = [
                                        'pending' => 'warning',
                                        'approved' => 'success',
                                        'revision' => 'danger',
                                    ][$journal->status] ?? 'secondary';

                                    $statusLabel = [
                                        'pending' => 'Menunggu Paraf',
                                        'approved' => 'Disetujui',
                                        'revision' => 'Perlu Revisi',
                                    ][$journal->status] ?? ucfirst($journal->status);
                                @endphp

                                <span class="badge bg-{{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            <td>
                                @if($journal->pembimbing_signed_at)
                                    <span class="text-success small fw-semibold">
                                        <i class="bi bi-check-circle-fill"></i>
                                        Sudah
                                    </span>
                                    <div class="text-muted small">
                                        {{ $journal->signer->name ?? '-' }}
                                    </div>
                                @else
                                    <span class="text-muted small">Belum</span>
                                @endif
                            </td>

                            <td style="min-width: 180px; white-space: normal;">
                                {{ $journal->revision_note ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-journal-x fs-2 text-muted"></i>
                                <p class="text-muted mt-2 mb-0">
                                    Belum ada data jurnal siswa.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($journals->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $journals->links() }}
        </div>
    @endif
</div>

@endsection
