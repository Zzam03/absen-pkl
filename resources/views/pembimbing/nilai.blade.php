
@extends('layouts.main')

@section('title', 'Penilaian Siswa - PKL Connect')
@section('page-heading', 'Penilaian Siswa')

@section('content')

{{-- HEADER --}}
<div class="mb-4">
    <a href="{{ route('pembimbing.siswa.show', $placement) }}"
       class="text-decoration-none small">
        <i class="bi bi-arrow-left"></i> Kembali ke detail siswa
    </a>

    <h4 class="fw-bold mt-2 mb-1">Form Penilaian PKL</h4>
    <p class="text-muted mb-0">
        Berikan penilaian berdasarkan hasil kegiatan siswa selama PKL.
    </p>
</div>

{{-- INFORMASI SISWA --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-3">Informasi Siswa</h5>

        <div class="row g-3">
            <div class="col-md-6">
                <small class="text-muted">Nama Siswa</small>
                <div class="fw-semibold">{{ $placement->student->name ?? '-' }}</div>
            </div>

            <div class="col-md-6">
                <small class="text-muted">Email</small>
                <div class="fw-semibold">{{ $placement->student->email ?? '-' }}</div>
            </div>

            <div class="col-md-6">
                <small class="text-muted">Periode PKL</small>
                <div class="fw-semibold">
                    {{ $placement->start_date?->format('d-m-Y') ?? '-' }}
                    sampai
                    {{ $placement->end_date?->format('d-m-Y') ?? '-' }}
                </div>
            </div>

            <div class="col-md-6">
                <small class="text-muted">Status Penempatan</small>
                <div>
                    <span class="badge bg-{{ $placement->status === 'active' ? 'success' : 'secondary' }}">
                        {{ ucfirst($placement->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- STATUS JURNAL --}}
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-3">
                <small class="text-muted">Jurnal Menunggu Paraf</small>
                <h3 class="fw-bold text-warning mb-0">{{ $jurnalPending }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-3">
                <small class="text-muted">Jurnal Disetujui</small>
                <h3 class="fw-bold text-success mb-0">{{ $jurnalApproved }}</h3>
            </div>
        </div>
    </div>
</div>

{{-- PESAN ERROR --}}
@if($jurnalPending > 0)
    <div class="alert alert-warning border-0 rounded-4">
        <div class="d-flex gap-2">
            <i class="bi bi-exclamation-triangle-fill fs-4"></i>
            <div>
                <strong>Penilaian belum bisa disimpan.</strong>
                <p class="mb-0">
                    Masih ada {{ $jurnalPending }} jurnal yang menunggu paraf atau revisi.
                    Selesaikan pemeriksaan jurnal terlebih dahulu.
                </p>
            </div>
        </div>
    </div>
@endif

{{-- FORM PENILAIAN --}}
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">

        <div class="mb-4">
            <h5 class="fw-bold mb-1">Komponen Penilaian</h5>
            <p class="text-muted small mb-0">
                Masukkan nilai antara 0 sampai 100 untuk setiap komponen.
            </p>
        </div>

        <form action="{{ route('pembimbing.nilai.store', $placement) }}"
              method="POST">
            @csrf

            @php
                $komponen = [
                    'discipline_score' => [
                        'label' => 'Kedisiplinan',
                        'icon' => 'bi-clock-history',
                        'description' => 'Ketepatan waktu, kehadiran, dan tanggung jawab.'
                    ],
                    'skill_score' => [
                        'label' => 'Keterampilan',
                        'icon' => 'bi-lightbulb',
                        'description' => 'Kemampuan dan keterampilan dalam menyelesaikan pekerjaan.'
                    ],
                    'attitude_score' => [
                        'label' => 'Sikap',
                        'icon' => 'bi-person-check',
                        'description' => 'Etika, komunikasi, dan kerja sama.'
                    ],
                    'report_score' => [
                        'label' => 'Laporan',
                        'icon' => 'bi-journal-text',
                        'description' => 'Kelengkapan dan kualitas laporan kegiatan PKL.'
                    ],
                ];
            @endphp

            <div class="row g-3">

                @foreach($komponen as $field => $item)
                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">

                            <label for="{{ $field }}" class="form-label fw-bold">
                                <i class="bi {{ $item['icon'] }} text-primary me-2"></i>
                                {{ $item['label'] }}
                            </label>

                            <p class="text-muted small">
                                {{ $item['description'] }}
                            </p>

                            <div class="input-group">
                                <input
                                    type="number"
                                    class="form-control @error($field) is-invalid @enderror"
                                    id="{{ $field }}"
                                    name="{{ $field }}"
                                    min="0"
                                    max="100"
                                    step="1"
                                    required
                                    value="{{ old($field, $score?->$field ?? '') }}"
                                    placeholder="Contoh: 85"
                                >

                                <span class="input-group-text">/ 100</span>

                                @error($field)
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>
                    </div>
                @endforeach

            </div>

            {{-- CATATAN PEMBIMBING --}}
            <div class="mt-4">
                <label for="pembimbing_note" class="form-label fw-bold">
                    Catatan Pembimbing
                </label>

                <textarea
                    name="pembimbing_note"
                    id="pembimbing_note"
                    rows="4"
                    maxlength="1000"
                    class="form-control @error('pembimbing_note') is-invalid @enderror"
                    placeholder="Tuliskan evaluasi, kelebihan, dan saran untuk siswa..."
                >{{ old('pembimbing_note', $score?->pembimbing_note ?? '') }}</textarea>

                @error('pembimbing_note')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- NILAI SEBELUMNYA --}}
            @if($score)
                <div class="alert alert-info mt-4 mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Siswa ini sudah memiliki nilai akhir:
                    <strong>{{ number_format((float) $score->final_score, 2) }}</strong>.
                    Menyimpan form ini akan memperbarui penilaian sebelumnya.
                </div>
            @endif

            {{-- BUTTON --}}
            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('pembimbing.siswa.show', $placement) }}"
                   class="btn btn-light border">
                    Batal
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                    {{ $jurnalPending > 0 ? 'disabled' : '' }}
                    onclick="return confirm('Apakah kamu yakin ingin menyimpan penilaian siswa ini?')"
                >
                    <i class="bi bi-save me-1"></i>
                    {{ $score ? 'Perbarui Penilaian' : 'Simpan Penilaian' }}
                </button>
            </div>

        </form>
    </div>
</div>

@endsection
