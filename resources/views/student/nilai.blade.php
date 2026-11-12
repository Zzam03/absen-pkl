
@extends('layouts.main')

@section('title', 'Nilai PKL - PKL Connect')
@section('page-heading', 'Nilai PKL')

@section('content')

<div class="mb-4">
    <h4 class="fw-bold mb-1">Hasil Penilaian PKL</h4>
    <p class="text-muted mb-0">
        Lihat hasil evaluasi dan pencapaian selama melaksanakan Praktik Kerja Lapangan.
    </p>
</div>

@if(!$placement)

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body text-center py-5">
            <i class="bi bi-clipboard-x text-muted" style="font-size: 3rem;"></i>
            <h5 class="fw-bold mt-3">Data Penempatan Belum Tersedia</h5>
            <p class="text-muted mb-0">
                Kamu belum memiliki data penempatan PKL.
                Silakan hubungi administrator.
            </p>
        </div>
    </div>

@else

    @php
        $score = $placement->score;
        $components = [
            'Disiplin' => $score?->discipline_score,
            'Keterampilan' => $score?->skill_score,
            'Sikap' => $score?->attitude_score,
            'Laporan' => $score?->report_score,
        ];

        $finalScore = $score?->final_score;
    @endphp

    {{-- INFORMASI PENEMPATAN --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <span class="badge bg-primary-subtle text-primary mb-2">
                        <i class="bi bi-building me-1"></i> Informasi PKL
                    </span>

                    <h5 class="fw-bold mb-2">
                        {{ $placement->location->name ?? 'Lokasi PKL' }}
                    </h5>

                    <p class="text-muted mb-1">
                        <i class="bi bi-person-badge me-2"></i>
                        Pembimbing: {{ $placement->pembimbing->name ?? '-' }}
                    </p>

                    <p class="text-muted mb-0">
                        <i class="bi bi-calendar-range me-2"></i>
                        {{ $placement->start_date?->format('d M Y') ?? '-' }}
                        -
                        {{ $placement->end_date?->format('d M Y') ?? '-' }}
                    </p>
                </div>

                @if($placement->status === 'finished')
                    <span class="badge bg-success">PKL Selesai</span>
                @elseif($placement->status === 'active')
                    <span class="badge bg-primary">Sedang Berlangsung</span>
                @else
                    <span class="badge bg-secondary">{{ ucfirst($placement->status) }}</span>
                @endif
            </div>
        </div>
    </div>

    @if(!$score)

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body text-center py-5">
                <div class="rounded-circle bg-warning-subtle d-inline-flex p-4 mb-3">
                    <i class="bi bi-hourglass-split text-warning" style="font-size: 2rem;"></i>
                </div>

                <h5 class="fw-bold">Nilai Belum Diterbitkan</h5>

                <p class="text-muted mb-0">
                    Pembimbing belum memberikan penilaian untuk kegiatan PKL kamu.
                    Silakan periksa kembali halaman ini nanti.
                </p>
            </div>
        </div>

    @else

        {{-- NILAI AKHIR --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4 text-center">
                <p class="text-muted fw-semibold mb-2">NILAI AKHIR PKL</p>

                <h1 class="display-3 fw-bold text-primary mb-2">
                    {{ $finalScore !== null ? number_format((float)$finalScore, 2) : '-' }}
                </h1>

                <span class="badge bg-success-subtle text-success px-3 py-2">
                    <i class="bi bi-check-circle-fill me-1"></i>
                    Nilai telah diterbitkan
                </span>

                <p class="text-muted small mt-3 mb-0">
                    Tanggal penilaian:
                    {{ $score->scored_at?->format('d F Y') ?? '-' }}
                </p>
            </div>
        </div>

        {{-- KOMPONEN PENILAIAN --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Rincian Penilaian</h5>

                @foreach($components as $label => $value)
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-semibold">{{ $label }}</span>
                            <strong>{{ $value !== null ? number_format((float)$value, 2) : '-' }}</strong>
                        </div>

                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar"
                                 role="progressbar"
                                 style="width: {{ $value !== null ? max(0, min(100, (float)$value)) : 0 }}%">
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>

        {{-- CATATAN PEMBIMBING --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-chat-left-text me-2 text-primary"></i>
                    Catatan Pembimbing
                </h5>

                @if($score->pembimbing_note)
                    <div class="bg-light rounded-3 p-3">
                        {{ $score->pembimbing_note }}
                    </div>
                @else
                    <p class="text-muted mb-0">Belum ada catatan dari pembimbing.</p>
                @endif

                <div class="text-muted small mt-3">
                    Dinilai oleh: {{ $score->scorer->name ?? $placement->pembimbing->name ?? '-' }}
                </div>
            </div>
        </div>

    @endif

@endif

@endsection
