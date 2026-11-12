
@extends('layouts.main')

@section('title', 'Tambah Jurnal - PKL Connect')
@section('page-heading', 'Tambah Jurnal')

@section('content')

<div class="mb-4">
    <a href="{{ route('student.jurnal.index') }}" class="text-decoration-none">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Jurnal
    </a>
    <h4 class="fw-bold mt-3 mb-1">Tambah Jurnal Harian</h4>
    <p class="text-muted mb-0">Tuliskan kegiatan yang kamu lakukan selama PKL.</p>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <form action="{{ route('student.jurnal.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="date" class="form-label fw-semibold">Tanggal Kegiatan</label>
                <input type="date"
                       name="date"
                       id="date"
                       class="form-control @error('date') is-invalid @enderror"
                       value="{{ old('date', date('Y-m-d')) }}"
                       max="{{ date('Y-m-d') }}"
                       required>
                @error('date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="activity" class="form-label fw-semibold">Deskripsi Kegiatan</label>
                <textarea name="activity"
                          id="activity"
                          rows="6"
                          maxlength="2000"
                          class="form-control @error('activity') is-invalid @enderror"
                          placeholder="Contoh: Membantu membuat tampilan halaman login menggunakan HTML dan CSS..."
                          required>{{ old('activity') }}</textarea>
                <div class="form-text">Minimal 10 karakter dan maksimal 2000 karakter.</div>
                @error('activity')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="photo" class="form-label fw-semibold">Foto Kegiatan (Opsional)</label>
                <input type="file"
                       name="photo"
                       id="photo"
                       accept="image/*"
                       class="form-control @error('photo') is-invalid @enderror">
                <div class="form-text">Format gambar, maksimal 4 MB.</div>
                @error('photo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="alert alert-info">
                <i class="bi bi-info-circle-fill me-2"></i>
                Jurnal yang dikirim akan berstatus <strong>Menunggu</strong> sampai diperiksa pembimbing.
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('student.jurnal.index') }}" class="btn btn-light">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send me-1"></i> Kirim Jurnal
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
