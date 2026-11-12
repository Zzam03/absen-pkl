
@extends('layouts.main')

@section('title', 'Rekap Jurnal')

@section('content')
<div class="container-fluid py-3">

    {{-- Header --}}
    <div class="mb-4">
        <h4 class="fw-bold mb-1">Rekap Jurnal PKL</h4>
        <p class="text-muted mb-0">
            Pantau aktivitas harian dan perkembangan jurnal siswa PKL.
        </p>
    </div>

    {{-- Filter --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-funnel me-2"></i>Filter Jurnal
            </h6>

            <form action="{{ route('admin.jurnal') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-5">
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

                    <div class="col-md-4">
                        <label class="form-label">Status Jurnal</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                Menunggu Pemeriksaan
                            </option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                                Disetujui
                            </option>
                            <option value="revision" {{ request('status') == 'revision' ? 'selected' : '' }}>
                                Perlu Revisi
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <a href="{{ route('admin.jurnal') }}" class="btn btn-light border flex-fill">
                            Reset
                        </a>
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bi bi-search me-1"></i> Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Jurnal --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div>
                    <h6 class="fw-bold mb-1">Data Jurnal Siswa</h6>
                    <small class="text-muted">
                        Total {{ $journals->total() }} jurnal tercatat
                    </small>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Tanggal</th>
                            <th>Nama Siswa</th>
                            <th>Aktivitas</th>
                            <th>Status</th>
                            <th>Pembimbing</th>
                            <th>Catatan Revisi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($journals as $journal)
                            <tr>
                                <td>{{ $journals->firstItem() + $loop->index }}</td>

                                <td class="text-nowrap">
                                    {{ $journal->date?->format('d/m/Y') ?? '-' }}
                                </td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $journal->student->name ?? '-' }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $journal->student->school_name ?? '-' }}
                                    </small>
                                </td>

                                <td style="min-width: 220px; max-width: 350px;">
                                    {{ $journal->activity }}
                                </td>

                                <td>
                                    @php
                                        $statusInfo = [
                                            'pending' => [
                                                'class' => 'warning',
                                                'label' => 'Menunggu'
                                            ],
                                            'approved' => [
                                                'class' => 'success',
                                                'label' => 'Disetujui'
                                            ],
                                            'revision' => [
                                                'class' => 'danger',
                                                'label' => 'Perlu Revisi'
                                            ],
                                        ];

                                        $info = $statusInfo[$journal->status] ?? [
                                            'class' => 'secondary',
                                            'label' => ucfirst($journal->status)
                                        ];
                                    @endphp

                                    <span class="badge bg-{{ $info['class'] }}">
                                        {{ $info['label'] }}
                                    </span>
                                </td>

                                <td>
                                    {{ $journal->signer->name ?? '-' }}
                                    @if($journal->pembimbing_signed_at)
                                        <br>
                                        <small class="text-muted">
                                            {{ $journal->pembimbing_signed_at->format('d/m/Y H:i') }}
                                        </small>
                                    @endif
                                </td>

                                <td style="min-width: 180px; max-width: 250px;">
                                    @if($journal->revision_note)
                                        <span class="text-danger">
                                            {{ $journal->revision_note }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-journal-text display-5 text-muted"></i>
                                    <h6 class="fw-bold mt-3">Belum Ada Data Jurnal</h6>
                                    <p class="text-muted mb-0">
                                        Jurnal siswa akan muncul di sini setelah siswa mengirimkan aktivitas PKL.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($journals->hasPages())
                <div class="mt-3">
                    {{ $journals->links('pagination::bootstrap-5') }}
                </div>
            @endif

        </div>
    </div>

</div>
@endsection
