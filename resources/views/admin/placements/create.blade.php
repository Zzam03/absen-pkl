
@extends('layouts.main')

@section('title', 'Tambah Penempatan')

@section('content')
<div class="container-fluid px-0">

    <div class="mb-4">
        <a href="{{ route('admin.placements.index') }}" class="text-decoration-none text-muted small">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Penempatan PKL
        </a>
        <h4 class="fw-bold mt-2 mb-1">Tambah Penempatan PKL</h4>
        <p class="text-muted mb-0">Hubungkan siswa dengan pembimbing dan lokasi perusahaan.</p>
    </div>

    <div class="card border-0 shadow-sm mx-auto" style="max-width: 850px;">
        <div class="card-header bg-white border-0 py-3">
            <h6 class="fw-bold mb-0">
                <i class="bi bi-clipboard-plus text-primary me-2"></i>Informasi Penempatan
            </h6>
        </div>

        <div class="card-body p-4">

            {{-- Informasi error --}}
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Periksa kembali data yang dimasukkan.</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.placements.store') }}" method="POST">
                @csrf

                {{-- Pilihan siswa --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Siswa PKL</label>
                    <select
                        name="student_id"
                        class="form-select @error('student_id') is-invalid @enderror"
                        required
                    >
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($students as $student)
                            <option
                                value="{{ $student->id }}"
                                @selected(old('student_id') == $student->id)
                            >
                                {{ $student->name }} ({{ $student->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if($students->isEmpty())
                        <div class="form-text text-danger">
                            Belum ada siswa aktif. Tambahkan siswa terlebih dahulu melalui Data Pengguna.
                        </div>
                    @endif
                </div>

                {{-- Pilihan pembimbing --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Pembimbing PKL</label>
                    <select
                        name="pembimbing_id"
                        class="form-select @error('pembimbing_id') is-invalid @enderror"
                        required
                    >
                        <option value="">-- Pilih Pembimbing --</option>
                        @foreach($pembimbings as $pembimbing)
                            <option
                                value="{{ $pembimbing->id }}"
                                @selected(old('pembimbing_id') == $pembimbing->id)
                            >
                                {{ $pembimbing->name }} ({{ $pembimbing->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('pembimbing_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if($pembimbings->isEmpty())
                        <div class="form-text text-danger">
                            Belum ada pembimbing aktif. Tambahkan pembimbing melalui Data Pengguna.
                        </div>
                    @endif
                </div>

                {{-- Pilihan lokasi --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Lokasi Perusahaan</label>
                    <select
                        name="location_id"
                        class="form-select @error('location_id') is-invalid @enderror"
                        required
                    >
                        <option value="">-- Pilih Lokasi --</option>
                        @foreach($locations as $location)
                            <option
                                value="{{ $location->id }}"
                                @selected(old('location_id') == $location->id)
                            >
                                {{ $location->name }}
                                @if($location->address)
                                    — {{ $location->address }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('location_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if($locations->isEmpty())
                        <div class="form-text text-danger">
                            Belum ada lokasi. Tambahkan lokasi melalui menu Lokasi PKL.
                        </div>
                    @endif
                </div>

                {{-- Periode PKL --}}
                <div class="border-top pt-4 mb-3">
                    <h6 class="fw-bold mb-1">Periode Pelaksanaan PKL</h6>
                    <p class="text-muted small mb-3">
                        Tentukan tanggal mulai dan tanggal berakhir penempatan.
                    </p>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tanggal Mulai</label>
                            <input
                                type="date"
                                name="start_date"
                                value="{{ old('start_date') }}"
                                class="form-control @error('start_date') is-invalid @enderror"
                                required
                            >
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tanggal Selesai</label>
                            <input
                                type="date"
                                name="end_date"
                                value="{{ old('end_date') }}"
                                class="form-control @error('end_date') is-invalid @enderror"
                                required
                            >
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="alert alert-info border-0 small">
                    <i class="bi bi-info-circle me-2"></i>
                    Penempatan baru akan otomatis memiliki status <strong>Aktif</strong>.
                    Pastikan siswa belum memiliki penempatan aktif lainnya.
                </div>

                {{-- Tombol --}}
                <div class="d-flex justify-content-end gap-2 border-top pt-3">
                    <a href="{{ route('admin.placements.index') }}" class="btn btn-light border">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan Penempatan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
