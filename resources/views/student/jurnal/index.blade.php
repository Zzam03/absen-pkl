
@extends('layouts.main')

@section('title', 'Jurnal Harian - PKL Connect')
@section('page-heading', 'Jurnal Harian')

@section('content')

<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1">Jurnal Harian Saya</h4>
        <p class="text-muted mb-0">Catat dan pantau kegiatan PKL setiap hari.</p>
    </div>

    <a href="{{ route('student.jurnal.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Jurnal
    </a>
</div>

<div class="row g-3 mb-4">
    @php
        $totalJurnal = $jurnals->total();
        $pending = $jurnals->getCollection()->where('status', 'pending')->count();
        $approved = $jurnals->getCollection()->where('status', 'approved')->count();
        $revision = $jurnals->getCollection()->where('status', 'revision')->count();
    @endphp

    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <small class="text-muted">Total Jurnal</small>
                <h3 class="fw-bold mb-0">{{ $totalJurnal }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <small class="text-muted">Menunggu</small>
                <h3 class="fw-bold text-warning mb-0">{{ $pending }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <small class="text-muted">Disetujui</small>
                <h3 class="fw-bold text-success mb-0">{{ $approved }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <small class="text-muted">Perlu Revisi</small>
                <h3 class="fw-bold text-danger mb-0">{{ $revision }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-3">Daftar Kegiatan</h5>

        @if($jurnals->count())
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Tanggal</th>
                            <th>Kegiatan</th>
                            <th>Status</th>
                            <th>Catatan Pembimbing</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jurnals as $index => $jurnal)
                            <tr>
                                <td>{{ $jurnals->firstItem() + $index }}</td>
                                <td class="text-nowrap">
                                    {{ $jurnal->date?->format('d-m-Y') ?? '-' }}
                                </td>
                                <td style="min-width: 220px; max-width: 350px;">
                                    {{ $jurnal->activity }}
                                    @if($jurnal->photo)
                                        <div class="mt-2">
                                            <a href="{{ asset('storage/'.$jurnal->photo) }}"
                                               target="_blank"
                                               class="small text-decoration-none">
                                                <i class="bi bi-image me-1"></i>Lihat foto
                                            </a>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($jurnal->status === 'approved')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($jurnal->status === 'revision')
                                        <span class="badge bg-danger">Perlu Revisi</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @endif
                                </td>
                                <td style="min-width: 180px;">
                                    {{ $jurnal->revision_note ?: '-' }}
                                </td>
                                <td class="text-center">
                                    @if($jurnal->status !== 'approved')
                                        <a href="{{ route('student.jurnal.edit', $jurnal) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                    @else
                                        <span class="text-muted small">Terkunci</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $jurnals->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-journal-x text-muted" style="font-size: 3rem;"></i>
                <h6 class="fw-bold mt-3">Belum Ada Jurnal</h6>
                <p class="text-muted">Mulai catat kegiatan PKL kamu hari ini.</p>
                <a href="{{ route('student.jurnal.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> Buat Jurnal Pertama
                </a>
            </div>
        @endif
    </div>
</div>

@endsection
