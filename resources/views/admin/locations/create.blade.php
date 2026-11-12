
@extends('layouts.main')

@section('title', 'Tambah Lokasi')

@section('content')
<div class="container-fluid px-0">

    <div class="mb-4">
        <a href="{{ route('admin.locations.index') }}" class="text-decoration-none text-muted small">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Lokasi PKL
        </a>
        <h4 class="fw-bold mt-2 mb-1">Tambah Lokasi PKL</h4>
        <p class="text-muted mb-0">Masukkan informasi perusahaan dan titik koordinat untuk absensi siswa.</p>
    </div>

    <div class="card border-0 shadow-sm mx-auto" style="max-width: 800px;">
        <div class="card-header bg-white border-0 py-3">
            <h6 class="fw-bold mb-0">
                <i class="bi bi-geo-alt text-primary me-2"></i>Informasi Lokasi
            </h6>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('admin.locations.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lokasi / Perusahaan</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="form-control @error('name') is-invalid @enderror"
                        placeholder="Contoh: PT Media Solusi Network"
                        maxlength="100"
                        required
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Alamat</label>
                    <textarea
                        name="address"
                        class="form-control @error('address') is-invalid @enderror"
                        rows="2"
                        maxlength="255"
                        placeholder="Masukkan alamat lengkap perusahaan"
                    >{{ old('address') }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="border-top pt-4 mb-3">
                    <h6 class="fw-bold mb-1">Koordinat GPS</h6>
                    <p class="text-muted small mb-3">
                        Koordinat digunakan untuk menentukan lokasi absensi siswa.
                    </p>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Latitude</label>
                            <input
                                type="number"
                                step="any"
                                name="latitude"
                                value="{{ old('latitude') }}"
                                class="form-control @error('latitude') is-invalid @enderror"
                                placeholder="Contoh: -6.914744"
                                required
                            >
                            @error('latitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Longitude</label>
                            <input
                                type="number"
                                step="any"
                                name="longitude"
                                value="{{ old('longitude') }}"
                                class="form-control @error('longitude') is-invalid @enderror"
                                placeholder="Contoh: 107.609810"
                                required
                            >
                            @error('longitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="alert alert-info border-0 small mb-4">
                        <i class="bi bi-info-circle me-1"></i>
                        <strong>Cara mengambil koordinat:</strong>
                        Buka Google Maps, klik kanan pada titik lokasi perusahaan, lalu klik angka koordinat untuk menyalinnya.
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Radius Absensi (meter)</label>
                    <input
                        type="number"
                        name="radius_meter"
                        value="{{ old('radius_meter', 100) }}"
                        min="10"
                        max="5000"
                        class="form-control @error('radius_meter') is-invalid @enderror"
                        required
                    >
                    <div class="form-text">Jarak maksimal siswa dari titik lokasi agar absensi dianggap berada di area perusahaan. Minimal 10 meter, maksimal 5.000 meter.</div>
                    @error('radius_meter')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2 border-top pt-3">
                    <a href="{{ route('admin.locations.index') }}" class="btn btn-light border">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan Lokasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
