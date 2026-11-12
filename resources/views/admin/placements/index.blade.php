
@extends('layouts.main')

@section('title', 'Data Penempatan PKL')

@section('content')
<div class="container-fluid px-0">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Penempatan PKL</h4>
            <p class="text-muted mb-0">Kelola penempatan siswa di perusahaan dan pembimbing PKL.</p>
        </div>

        <a href="{{ route('admin.placements.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Penempatan
        </a>
    </div>

    {{-- Ringkasan --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-primary bg-opacity-10 text-primary p-3">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Penempatan</div>
                        <h4 class="fw-bold mb-0">{{ $placements->total() }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-success bg-opacity-10 text-success p-3">
                        <i class="bi bi-check-circle fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Penempatan Aktif</div>
                        <h4 class="fw-bold mb-0">
                            {{ $placements->getCollection()->where('status', 'active')->count() }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-warning bg-opacity-10 text-warning p-3">
                        <i class="bi bi-clock-history fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Penempatan Selesai</div>
                        <h4 class="fw-bold mb-0">
                            {{ $placements->getCollection()->where('status', 'finished')->count() }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <h6 class="fw-bold mb-0">Daftar Penempatan Siswa</h6>
            <small class="text-muted">Informasi siswa, pembimbing, lokasi, dan periode PKL.</small>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">No.</th>
                        <th>Siswa</th>
                        <th>Pembimbing</th>
                        <th>Lokasi PKL</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($placements as $placement)
                        <tr>
                            <td class="ps-3 text-muted">
                                {{ $placements->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $placement->student->name ?? 'Siswa tidak ditemukan' }}
                                </div>
                                <small class="text-muted">
                                    {{ $placement->student->email ?? '-' }}
                                </small>
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $placement->pembimbing->name ?? '-' }}
                                </div>
                                <small class="text-muted">
                                    Pembimbing PKL
                                </small>
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $placement->location->name ?? '-' }}
                                </div>
                                <small class="text-muted">
                                    {{ $placement->location->address ?? 'Alamat belum tersedia' }}
                                </small>
                            </td>

                            <td>
                                <div class="small">
                                    {{ $placement->start_date?->format('d/m/Y') ?? '-' }}
                                    <div class="text-muted">
                                        s.d. {{ $placement->end_date?->format('d/m/Y') ?? '-' }}
                                    </div>
                                </div>
                            </td>

                            <td>
                                @if($placement->status === 'active')
                                    <span class="badge bg-success bg-opacity-10 text-success">Aktif</span>
                                @elseif($placement->status === 'finished')
                                    <span class="badge bg-primary bg-opacity-10 text-primary">Selesai</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger">Dibatalkan</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a
                                        href="{{ route('admin.placements.edit', $placement) }}"
                                        class="btn btn-sm btn-outline-warning"
                                        title="Edit penempatan"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form
                                        action="{{ route('admin.placements.destroy', $placement) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus penempatan ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Hapus penempatan"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-clipboard-x fs-1 text-muted"></i>
                                <h6 class="fw-semibold mt-2">Belum Ada Penempatan</h6>
                                <p class="text-muted small mb-3">
                                    Belum ada siswa yang ditempatkan di lokasi PKL.
                                </p>
                                <a href="{{ route('admin.placements.create') }}" class="btn btn-primary btn-sm">
                                    Tambah Penempatan
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white border-0 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <small class="text-muted">
                Menampilkan {{ $placements->firstItem() ?? 0 }}–{{ $placements->lastItem() ?? 0 }}
                dari {{ $placements->total() }} penempatan.
            </small>

            {{ $placements->links('pagination::bootstrap-5') }}
        </div>
    </div>

</div>
@endsection
