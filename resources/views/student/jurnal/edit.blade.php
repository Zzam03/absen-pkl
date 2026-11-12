
@extends('layouts.main')

@section('title', 'Edit Jurnal - PKL Connect')
@section('page-heading', 'Edit Jurnal')

@section('content')

<div class="mb-4">
    <a href="{{ route('student.jurnal.index') }}" class="text-decoration-none">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Jurnal
    </a>
    <h4 class="fw-bold mt-3 mb-1">Edit Jurnal Harian</h4>
    <p class="text-muted mb-0">Perbaiki kegiatan atau unggah foto baru.</p>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">

        <div class="mb-3">
            <span class="text-muted small">Tanggal kegiatan</span>
            <h6 class="fw-bold">{{ $journal->date?->format('d F Y') ?? '-' }}</h6>
        </div>

        @if($journal->revision_note)
            <div class="alert alert-warning">
                <strong><i class="bi bi-exclamation-triangle-fill me-2"></i>Catatan Pembimbing</strong>
                <p class="mb-0 mt-2">{{ $journal->revision_note }}</p>
            </div>
        @endif

        <form action="{{ route('student.jurnal.update', $journal) }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="activity" class="form-label fw-semibold">Deskripsi Kegiatan</label>
                <textarea name="activity"
                          id="activity"
                          rows="6"
                          maxlength="2000"
                          class="form-control @error('activity') is-invalid @enderror"
                          required>{{ old('activity', $journal->activity) }}</textarea>
                <div class="form-text">Minimal 10 karakter dan maksimal 2000 karakter.</div>
                @error('activity')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            @if($journal->photo)
                <div class="mb-3">
                    <label class="form-label fw-semibold">Foto Saat Ini</label>
                    <div>
                        <a href="{{ asset('storage/'.$journal->photo) }}" target="_blank">
                            <img src="{{ asset('storage/'.$journal->photo) }}"
                                 alt="Foto jurnal"
                                 class="img-thumbnail"
                                 style="max-width: 220px; max-height: 160px; object-fit: cover;">
                        </a>
                    </div>
                </div>
            @endif

            <div class="mb-4">
                <label for="photo" class="form-label fw-semibold">Ganti Foto (Opsional)</label>
                <input type="file"
                       name="photo"
                       id="photo"
                       accept="image/*"
                       class="form-control @error('photo') is-invalid @enderror">
                <div class="form-text">Kosongkan jika tidak ingin mengganti foto. Maksimal 4 MB.</div>
                @error('photo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="alert alert-info">
                <i class="bi bi-info-circle-fill me-2"></i>
                Setelah disimpan, jurnal akan dikirim ulang dengan status <strong>Menunggu</strong>.
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('student.jurnal.index') }}" class="btn btn-light">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
</div>

@endsection
