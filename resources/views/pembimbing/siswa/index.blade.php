
@extends('layouts.main')

@section('title', 'Siswa Bimbingan - PKL Connect')
@section('page-heading', 'Siswa Bimbingan')

@section('content')

<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1">Daftar Siswa Bimbingan</h4>
        <p class="text-muted mb-0">Pantau penempatan dan perkembangan siswa PKL.</p>
    </div>

    <span class="badge bg-primary-subtle text-primary px-3 py-2">
        {{ $placements->count() }} Penempatan
    </span>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-3">
                <small class="text-muted">Total Siswa</small>
                <h3 class="fw-bold mb-0">{{ $placements->count() }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-3">
                <small class="text-muted">Penempatan Aktif</small>
                <h3 class="fw-bold text-success mb-0">
                    {{ $placements->where('status', 'active')->count() }}
                </h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-3">
                <small class="text-muted">Nilai Sudah Terbit</small>
                <h3 class="fw-bold text-primary mb-0">
                    {{ $placements->filter(fn($p) => $p->score !== null)->count() }}
                </h3>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">

        <h5 class="fw-bold mb-3">Data Siswa</h5>

        @if($placements->count())
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Nama Siswa</th>
                            <th>Sekolah</th>
                            <th>Lokasi PKL</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th>Nilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($placements as $index => $placement)
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $placement->student->name ?? '-' }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $placement->student->email ?? '-' }}
                                    </small>
                                </td>

                                <td>{{ $placement->student->school_name ?? '-' }}</td>

                                <td>{{ $placement->location->name ?? '-' }}</td>

                                <td class="text-nowrap">
                                    <small>
                                        {{ $placement->start_date?->format('d-m-Y') ?? '-' }}
                                        <br>
                                        s.d. {{ $placement->end_date?->format('d-m-Y') ?? '-' }}
                                    </small>
                                </td>

                                <td>
                                    @if($placement->status === 'active')
                                        <span class="badge bg-success">Aktif</span>
                                    @elseif($placement->status === 'finished')
                                        <span class="badge bg-primary">Selesai</span>
                                    @else
                                        <span class="badge bg-secondary">
                                            {{ ucfirst($placement->status) }}
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if($placement->score)
                                        <span class="badge bg-success">
                                            {{ number_format((float)$placement->score->final_score, 2) }}
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">Belum Dinilai</span>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route('pembimbing.siswa.show', $placement) }}"
                                       class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye me-1"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                <h6 class="fw-bold mt-3">Belum Ada Siswa Bimbingan</h6>
                <p class="text-muted mb-0">
                    Data penempatan siswa yang menjadi tanggung jawabmu akan muncul di sini.
                </p>
            </div>
        @endif

    </div>
</div>

@endsection
