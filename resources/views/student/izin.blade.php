
@extends('layouts.main')

@section('title', 'Pengajuan Izin / Sakit')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h3 class="fw-bold">Pengajuan Izin / Sakit</h3>
        <p class="text-muted">Gunakan halaman ini jika kamu tidak dapat mengikuti kegiatan PKL.</p>
    </div>

    @if (!$placement)
        <div class="alert alert-warning">
            Kamu tidak memiliki penempatan PKL yang aktif hari ini.
        </div>

    @elseif ($attendance)
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Status Pengajuan Hari Ini</h5>

                @if ($attendance->status === 'izin')
                    <span class="badge bg-warning text-dark">Izin</span>
                @elseif ($attendance->status === 'sakit')
                    <span class="badge bg-info text-dark">Sakit</span>
                @else
                    <span class="badge bg-success">Sudah Absen</span>
                @endif

                <p class="mt-3 mb-1">
                    <strong>Tanggal:</strong>
                    {{ \Carbon\Carbon::parse($attendance->date)->format('d-m-Y') }}
                </p>

                <p class="mb-0">
                    <strong>Keterangan:</strong>
                    {{ $attendance->note ?? '-' }}
                </p>

                <div class="alert alert-info mt-3 mb-0">
                    Absensi hari ini sudah tercatat. Kamu tidak dapat mengajukan izin atau sakit lagi untuk tanggal yang sama.
                </div>
            </div>
        </div>

    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Form Pengajuan</h5>

                <div class="alert alert-secondary">
                    <i class="bi bi-info-circle"></i>
                    Pengajuan ini akan langsung dicatat pada riwayat absensi kamu.
                </div>

                <form action="{{ route('student.izin.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="status" class="form-label">Jenis Pengajuan</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="">-- Pilih jenis pengajuan --</option>
                            <option value="izin" {{ old('status') == 'izin' ? 'selected' : '' }}>
                                Izin
                            </option>
                            <option value="sakit" {{ old('status') == 'sakit' ? 'selected' : '' }}>
                                Sakit
                            </option>
                        </select>
                        @error('status')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="note" class="form-label">Alasan / Keterangan</label>
                        <textarea
                            name="note"
                            id="note"
                            rows="4"
                            class="form-control"
                            placeholder="Tuliskan alasan pengajuan izin atau sakit..."
                            required
                        >{{ old('note') }}</textarea>
                        @error('note')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send"></i> Kirim Pengajuan
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection
