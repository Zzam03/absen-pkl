@extends('layouts.main')

@section('title', 'Persetujuan Izin / Sakit')
@section('page-heading', 'Persetujuan Izin / Sakit')

@section('content')
<div class="container-fluid px-0">

    <div class="mb-4">
        <h3 class="fw-bold mb-1">Pengajuan Izin / Sakit</h3>
        <p class="text-muted mb-0">Periksa pengajuan siswa dan berikan persetujuan.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">Menunggu Persetujuan</div>
                    <h3 class="fw-bold text-warning mb-0">
                        {{ $leaveRequests->getCollection()->where('approval_status', 'pending')->count() }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">Disetujui</div>
                    <h3 class="fw-bold text-success mb-0">
                        {{ $leaveRequests->getCollection()->where('approval_status', 'approved')->count() }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">Ditolak</div>
                    <h3 class="fw-bold text-danger mb-0">
                        {{ $leaveRequests->getCollection()->where('approval_status', 'rejected')->count() }}
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 p-4">
            <h5 class="fw-bold mb-1">Daftar Pengajuan</h5>
            <small class="text-muted">Pengajuan terbaru ditampilkan terlebih dahulu.</small>
        </div>

        <div class="card-body p-0">
            @forelse($leaveRequests as $item)
                <div class="p-4 border-top">

                    <div class="d-flex flex-wrap justify-content-between gap-3 mb-3">
                        <div>
                            <h6 class="fw-bold mb-1">
                                {{ $item->student->name ?? 'Siswa tidak ditemukan' }}
                            </h6>
                            <div class="text-muted small">
                                {{ $item->placement->location->name ?? 'Lokasi PKL tidak tersedia' }}
                            </div>
                        </div>

                        <div>
                            @if($item->approval_status === 'pending')
                                <span class="badge bg-warning text-dark">Menunggu Persetujuan</span>
                            @elseif($item->approval_status === 'approved')
                                <span class="badge bg-success">Disetujui</span>
                            @else
                                <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <small class="text-muted d-block">Tanggal Izin</small>
                            <strong>{{ \Carbon\Carbon::parse($item->date)->translatedFormat('d F Y') }}</strong>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted d-block">Jenis Pengajuan</small>
                            @if($item->status === 'izin')
                                <span class="badge bg-warning text-dark">Izin</span>
                            @else
                                <span class="badge bg-info text-dark">Sakit</span>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted d-block">Diajukan Pada</small>
                            <strong>{{ $item->created_at->format('d-m-Y H:i') }}</strong>
                        </div>
                    </div>

                    <div class="bg-light rounded-3 p-3 mb-3">
                        <small class="text-muted d-block mb-1">Alasan Siswa</small>
                        <div>{{ $item->note }}</div>
                    </div>

                    @if($item->approval_status !== 'pending')
                        <div class="small text-muted mb-3">
                            Diproses oleh:
                            <strong>{{ $item->reviewer->name ?? '-' }}</strong>

                            @if($item->reviewed_at)
                                pada {{ $item->reviewed_at->format('d-m-Y H:i') }}
                            @endif

                            @if($item->review_note)
                                <div class="mt-2">
                                    Catatan pembimbing: {{ $item->review_note }}
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($item->approval_status === 'pending')
                        <div class="d-flex flex-wrap gap-2">

                            <form
                                action="{{ route('pembimbing.izin.approve', $item->id) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin ingin menyetujui pengajuan ini?')"
                            >
                                @csrf
                                @method('PUT')

                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Setujui
                                </button>
                            </form>

                            <form
                                action="{{ route('pembimbing.izin.reject', $item->id) }}"
                                method="POST"
                                class="d-flex flex-wrap gap-2"
                                onsubmit="return confirm('Yakin ingin menolak pengajuan ini?')"
                            >
                                @csrf
                                @method('PUT')

                                <input
                                    type="text"
                                    name="review_note"
                                    class="form-control"
                                    placeholder="Alasan penolakan (opsional)"
                                    maxlength="1000"
                                    style="max-width: 260px;"
                                >

                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="bi bi-x-circle me-1"></i>
                                    Tolak
                                </button>
                            </form>

                        </div>
                    @endif

                </div>
            @empty
                <div class="text-center py-5 px-3">
                    <i class="bi bi-calendar-check display-4 text-muted"></i>
                    <h5 class="fw-bold mt-3">Belum Ada Pengajuan</h5>
                    <p class="text-muted mb-0">
                        Pengajuan izin atau sakit dari siswa akan muncul di sini.
                    </p>
                </div>
            @endforelse
        </div>

        @if($leaveRequests->hasPages())
            <div class="card-footer bg-white border-0 p-3">
                {{ $leaveRequests->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
