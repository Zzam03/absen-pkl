@extends('layouts.main')

@section('title', 'Pengajuan Izin / Sakit')
@section('page-heading', 'Izin / Sakit')

@section('content')
<div class="container-fluid px-0">

    <div class="mb-4">
        <h3 class="fw-bold mb-1">Pengajuan Izin / Sakit</h3>
        <p class="text-muted mb-0">
            Ajukan izin atau sakit dan tunggu persetujuan dari pembimbing PKL.
        </p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Pengajuan belum berhasil:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($placement)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 p-4 pb-0">
                <h5 class="fw-bold mb-1">
                    <i class="bi bi-calendar-plus text-primary me-2"></i>
                    Form Pengajuan
                </h5>
                <p class="text-muted small mb-0">
                    Pilih tanggal dan isi keterangan pengajuanmu.
                </p>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('student.izin.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="date" class="form-label fw-semibold">
                                Tanggal Izin / Sakit
                            </label>
                            <input
                                type="date"
                                name="date"
                                id="date"
                                class="form-control"
                                min="{{ today()->toDateString() }}"
                                value="{{ old('date') }}"
                                required
                            >
                            <small class="text-muted">
                                Pilih tanggal melalui kalender pada kolom ini.
                            </small>
                            @error('date')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label fw-semibold">
                                Jenis Pengajuan
                            </label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="">Pilih jenis pengajuan</option>
                                <option value="izin" {{ old('status') === 'izin' ? 'selected' : '' }}>
                                    Izin
                                </option>
                                <option value="sakit" {{ old('status') === 'sakit' ? 'selected' : '' }}>
                                    Sakit
                                </option>
                            </select>
                            @error('status')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="note" class="form-label fw-semibold">
                                Alasan / Keterangan
                            </label>
                            <textarea
                                name="note"
                                id="note"
                                rows="4"
                                class="form-control"
                                placeholder="Jelaskan alasan pengajuan izin atau sakit..."
                                required
                            >{{ old('note') }}</textarea>
                            @error('note')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="alert alert-info mt-3 mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        Pengajuan akan berstatus menunggu sampai pembimbing memberikan persetujuan.
                        Jika disetujui, status izin atau sakit akan masuk ke riwayat absensi.
                    </div>

                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-send me-2"></i>
                        Kirim Pengajuan
                    </button>
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-warning border-0 shadow-sm mb-4">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Kamu belum memiliki penempatan PKL yang aktif. Pengajuan belum dapat dilakukan.
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 p-4">
            <h5 class="fw-bold mb-1">
                <i class="bi bi-clock-history text-primary me-2"></i>
                Riwayat Pengajuan
            </h5>
            <p class="text-muted small mb-0">
                Pantau status pengajuan izin dan sakitmu.
            </p>
        </div>

        <div class="card-body p-0">
            @forelse($leaveRequests as $item)
                <div class="p-4 border-top">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                        <div>
                            <div class="fw-bold">
                                {{ $item->status === 'izin' ? 'Pengajuan Izin' : 'Pengajuan Sakit' }}
                            </div>
                            <small class="text-muted">
                                {{ $item->date->translatedFormat('d F Y') }}
                            </small>
                        </div>

                        <div>
                            @if($item->approval_status === 'pending')
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-hourglass-split me-1"></i> Menunggu Persetujuan
                                </span>
                            @elseif($item->approval_status === 'approved')
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i> Disetujui
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="bi bi-x-circle me-1"></i> Ditolak
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="bg-light rounded-3 p-3">
                        <small class="text-muted d-block mb-1">Alasan Pengajuan</small>
                        <div>{{ $item->note }}</div>
                    </div>

                    @if($item->approval_status !== 'pending')
                        <div class="small text-muted mt-3">
                            <div>
                                Diproses oleh:
                                <strong>{{ $item->reviewer->name ?? '-' }}</strong>
                            </div>

                            @if($item->reviewed_at)
                                <div>
                                    Tanggal diproses:
                                    {{ $item->reviewed_at->format('d-m-Y H:i') }}
                                </div>
                            @endif

                            @if($item->review_note)
                                <div class="mt-2">
                                    Catatan pembimbing:
                                    <strong>{{ $item->review_note }}</strong>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-5 px-3">
                    <i class="bi bi-calendar2-x display-4 text-muted"></i>
                    <h6 class="fw-bold mt-3">Belum Ada Pengajuan</h6>
                    <p class="text-muted mb-0">
                        Riwayat pengajuan izin atau sakit akan muncul di sini.
                    </p>
                </div>
            @endforelse
        </div>

    </div>

</div>
@endsection
