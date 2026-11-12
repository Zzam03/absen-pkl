
@extends('layouts.main')

@section('title', 'Data Lokasi PKL')

@section('content')
<div class="container-fluid px-0">

    {{-- Header halaman --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Lokasi PKL</h4>
            <p class="text-muted mb-0">Kelola lokasi perusahaan dan pengaturan radius absensi siswa.</p>
        </div>

        <a href="{{ route('admin.locations.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Lokasi
        </a>
    </div>

    {{-- Ringkasan --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-primary bg-opacity-10 text-primary p-3">
                        <i class="bi bi-geo-alt fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Lokasi</div>
                        <h4 class="fw-bold mb-0">{{ $locations->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-success bg-opacity-10 text-success p-3">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Penempatan</div>
                        <h4 class="fw-bold mb-0">{{ $locations->sum('placements_count') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-warning bg-opacity-10 text-warning p-3">
                        <i class="bi bi-radar fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Pengaturan Radius</div>
                        <h4 class="fw-bold mb-0">GPS</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel lokasi --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <h6 class="fw-bold mb-0">Daftar Lokasi Perusahaan</h6>
            <small class="text-muted">Daftar lokasi yang digunakan untuk penempatan PKL.</small>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">No.</th>
                        <th>Nama Lokasi</th>
                        <th>Alamat</th>
                        <th>Koordinat</th>
                        <th>Radius Absensi</th>
                        <th>Penempatan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($locations as $location)
                        <tr>
                            <td class="ps-3 text-muted">{{ $loop->iteration }}</td>

                            <td>
                                <div class="fw-semibold">{{ $location->name }}</div>
                                <small class="text-muted">Lokasi PKL</small>
                            </td>

                            <td>
                                <span class="text-muted">
                                    {{ $location->address ?: 'Alamat belum diisi' }}
                                </span>
                            </td>

                            <td>
                                <div class="small">
                                    <div>Lat: {{ $location->latitude }}</div>
                                    <div>Long: {{ $location->longitude }}</div>
                                </div>
                                <a
                                    href="https://www.google.com/maps?q={{ $location->latitude }},{{ $location->longitude }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="small text-decoration-none"
                                >
                                    <i class="bi bi-map me-1"></i>Lihat peta
                                </a>
                            </td>

                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info">
                                    {{ $location->radius_meter }} meter
                                </span>
                            </td>

                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                    {{ $location->placements_count }} penempatan
                                </span>
                            </td>

                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a
                                        href="{{ route('admin.locations.edit', $location) }}"
                                        class="btn btn-sm btn-outline-warning"
                                        title="Edit lokasi"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form
                                        action="{{ route('admin.locations.destroy', $location) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus lokasi ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Hapus lokasi"
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
                                <i class="bi bi-geo-alt fs-1 text-muted"></i>
                                <h6 class="fw-semibold mt-2">Belum Ada Lokasi</h6>
                                <p class="text-muted small mb-3">
                                    Tambahkan lokasi perusahaan PKL terlebih dahulu.
                                </p>
                                <a href="{{ route('admin.locations.create') }}" class="btn btn-primary btn-sm">
                                    Tambah Lokasi
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white border-0 text-muted small">
            Menampilkan {{ $locations->count() }} lokasi.
        </div>
    </div>

</div>
@endsection
