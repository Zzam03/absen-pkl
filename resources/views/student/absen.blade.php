
@extends('layouts.main')

@section('title', 'Absensi')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h3 class="fw-bold">Absensi PKL</h3>
        <p class="text-muted">Catat kehadiran dan kepulangan kamu di lokasi PKL.</p>
    </div>

    @if (!$placement)
        <div class="alert alert-warning">
            Kamu tidak memiliki penempatan PKL yang aktif hari ini.
        </div>
    @else
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-bold">{{ $placement->location->name }}</h5>
                <p class="text-muted mb-1">{{ $placement->location->address }}</p>
                <small class="text-muted">
                    Radius lokasi: {{ $placement->location->radius_meter }} meter
                </small>
            </div>
        </div>

        @if ($attendance && in_array($attendance->status, ['izin', 'sakit']))
            <div class="alert alert-info">
                Absensi hari ini tercatat sebagai
                <strong>{{ ucfirst($attendance->status) }}</strong>.
                <br>
                Keterangan: {{ $attendance->note }}
            </div>

        @elseif ($attendance && $attendance->check_out_time)
            <div class="alert alert-success">
                <h5 class="fw-bold mb-1">Absensi selesai!</h5>
                Kamu sudah melakukan absen masuk dan pulang hari ini.
            </div>

        @else
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Status Kehadiran Hari Ini</h5>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="p-3 rounded bg-light">
                                <small class="text-muted">Absen Masuk</small>
                                <h5 class="mb-0">
                                    {{ $attendance?->check_in_time ?? 'Belum absen' }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded bg-light">
                                <small class="text-muted">Absen Pulang</small>
                                <h5 class="mb-0">
                                    {{ $attendance?->check_out_time ?? 'Belum absen' }}
                                </h5>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-secondary">
                        <i class="bi bi-geo-alt"></i>
                        Tekan tombol Ambil Lokasi sebelum melakukan absensi.
                    </div>

                    <button type="button" class="btn btn-outline-primary mb-4" id="btnLokasi">
                        <i class="bi bi-geo-alt"></i> Ambil Lokasi Saya
                    </button>

                    <p id="statusLokasi" class="small text-muted">
                        Lokasi belum diambil.
                    </p>

                    @if (!$attendance || !$attendance->check_in_time)
                        <form action="{{ route('student.absen.masuk') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="latitude" class="latitude">
                            <input type="hidden" name="longitude" class="longitude">

                            <div class="mb-3">
                                <label class="form-label">Foto Absensi (Opsional)</label>
                                <input type="file" name="photo" class="form-control" accept="image/*" capture="environment">
                            </div>

                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-box-arrow-in-right"></i> Absen Masuk
                            </button>
                        </form>
                    @else
                        <form action="{{ route('student.absen.pulang') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="latitude" class="latitude">
                            <input type="hidden" name="longitude" class="longitude">

                            <div class="mb-3">
                                <label class="form-label">Foto Pulang (Opsional)</label>
                                <input type="file" name="photo" class="form-control" accept="image/*" capture="environment">
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-box-arrow-right"></i> Absen Pulang
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endif
    @endif
</div>
@endsection

@push('scripts')
<script>
document.getElementById('btnLokasi')?.addEventListener('click', function () {
    const tombol = this;
    const status = document.getElementById('statusLokasi');

    if (!navigator.geolocation) {
        status.textContent = 'Browser kamu tidak mendukung fitur lokasi.';
        return;
    }

    tombol.disabled = true;
    status.textContent = 'Sedang mengambil lokasi...';

    navigator.geolocation.getCurrentPosition(
        function (position) {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;

            document.querySelectorAll('.latitude').forEach(el => el.value = latitude);
            document.querySelectorAll('.longitude').forEach(el => el.value = longitude);

            status.textContent = 'Lokasi berhasil diambil. Kamu bisa melakukan absensi.';
            tombol.disabled = false;
        },
        function (error) {
            status.textContent = 'Gagal mengambil lokasi. Pastikan izin lokasi di browser sudah diaktifkan.';
            tombol.disabled = false;
        },
        {
            enableHighAccuracy: true,
            timeout: 15000,
            maximumAge: 0
        }
    );
});
</script>
@endpush
