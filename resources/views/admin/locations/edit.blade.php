
@extends('layouts.main')

@section('title', 'Edit Lokasi')

@section('content')
<div class="container-fluid px-0">

    <div class="mb-4">
        <a href="{{ route('admin.locations.index') }}" class="text-decoration-none text-muted small">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Lokasi PKL
        </a>
        <h4 class="fw-bold mt-2 mb-1">Edit Lokasi PKL</h4>
        <p class="text-muted mb-0">Perbarui informasi dan pengaturan absensi lokasi.</p>
    </div>

    <div class="card border-0 shadow-sm mx-auto" style="max-width: 800px;">
        <div class="card-header bg-white border-0 py-3">
            <h6 class="fw-bold mb-0">
                <i class="bi bi-pencil-square text-warning me-2"></i>Edit Informasi Lokasi
            </h6>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('admin.locations.update', $location) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lokasi / Perusahaan</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $location->name) }}"
                        class="form-control @error('name') is-invalid @enderror"
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
                    >{{ old('address', $location->address) }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="border-top pt-4 mb-3">
                    <h6 class="fw-bold mb-1">Koordinat GPS</h6>
                    <p class="text-muted small mb-3">
                        Pastikan koordinat sesuai dengan titik lokasi perusahaan.
                    </p>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Latitude</label>
                            <input
                                type="number"
                                step="any"
                                name="latitude"
                                value="{{ old('latitude', $location->latitude) }}"
                                class="form-control @error('latitude') is-invalid @enderror"
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
                                value="{{ old('longitude', $location->longitude) }}"
                                class="form-control @error('longitude') is-invalid @enderror"
                                required
                            >
                            @error('longitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Radius Absensi (meter)</label>
                    <input
                        type="number"
                        name="radius_meter"
                        value="{{ old('radius_meter', $location->radius_meter) }}"
                        min="10"
                        max="5000"
                        class="form-control @error('radius_meter') is-invalid @enderror"
                        required
                    >
                    <div class="form-text">Minimal 10 meter dan maksimal 5.000 meter.</div>
                    @error('radius_meter')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2 border-top pt-3">
                    <a href="{{ route('admin.locations.index') }}" class="btn btn-light border">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
