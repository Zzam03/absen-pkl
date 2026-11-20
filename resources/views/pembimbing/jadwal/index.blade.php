
@extends('layouts.main')

@section('title', 'Kelola Jadwal - PKL Connect')
@section('page-heading', 'Kelola Jadwal Siswa')

@section('content')

<div class="container-fluid px-0">

    {{-- HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Jadwal Siswa Bimbingan</h4>
            <p class="text-muted mb-0">
                Atur jam masuk, jam pulang, dan hari libur siswa PKL.
            </p>
        </div>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Jadwal gagal diperbarui.</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FILTER BULAN --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3 p-md-4">
            <form action="{{ route('pembimbing.jadwal.index') }}" method="GET"
                  class="row align-items-end g-3">
                <div class="col-md-5">
                    <label for="month" class="form-label fw-semibold">
                        Pilih Bulan Jadwal
                    </label>
                    <input type="month"
                           name="month"
                           id="month"
                           class="form-control"
                           value="{{ $month }}"
                           required>
                </div>

                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-search me-1"></i> Tampilkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- DAFTAR JADWAL --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 px-3 px-md-4 pt-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-1">Daftar Jadwal</h5>
                    <p class="text-muted small mb-0">
                        Periode {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') }}
                    </p>
                </div>
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                    {{ $schedules->total() }} Jadwal
                </span>
            </div>
        </div>

        <div class="card-body p-0">
            @forelse($schedules as $schedule)
                <div class="border-top p-3 p-md-4">

                    <div class="row align-items-start g-3">

                        {{-- INFORMASI SISWA --}}
                        <div class="col-lg-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                                     style="width:42px;height:42px;">
                                    <i class="bi bi-person-fill fs-5"></i>
                                </div>

                                <div>
                                    <div class="fw-bold">
                                        {{ $schedule->placement->student->name ?? 'Siswa tidak ditemukan' }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $schedule->placement->location->name ?? 'Lokasi PKL' }}
                                    </small>
                                </div>
                            </div>

                            <div class="small text-muted">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ $schedule->date->translatedFormat('l, d F Y') }}
                            </div>
                        </div>

                        {{-- FORM UPDATE --}}
                        <div class="col-lg-9">
                            <form action="{{ route('pembimbing.jadwal.update', $schedule->id) }}"
                                  method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row g-3">

                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold">
                                            Jam Masuk
                                        </label>
                                        <input type="time"
                                               name="start_time"
                                               class="form-control"
                                               value="{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}"
                                               required>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold">
                                            Jam Pulang
                                        </label>
                                        <input type="time"
                                               name="end_time"
                                               class="form-control"
                                               value="{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}"
                                               required>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold">
                                            Status Jadwal
                                        </label>
                                        <select name="status" class="form-select" required>
                                            <option value="aktif"
                                                {{ $schedule->status === 'aktif' ? 'selected' : '' }}>
                                                Aktif
                                            </option>
                                            <option value="libur"
                                                {{ $schedule->status === 'libur' ? 'selected' : '' }}>
                                                Libur
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold">
                                            Keterangan
                                        </label>
                                        <input type="text"
                                               name="activity"
                                               class="form-control"
                                               maxlength="500"
                                               value="{{ $schedule->activity }}"
                                               placeholder="Contoh: Pulang lebih awal">
                                    </div>

                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary btn-sm px-4">
                                            <i class="bi bi-save me-1"></i>
                                            Simpan Perubahan
                                        </button>
                                    </div>

                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            @empty
                <div class="text-center py-5 px-3">
                    <i class="bi bi-calendar-x text-muted" style="font-size:48px;"></i>
                    <h6 class="fw-bold mt-3">Belum Ada Jadwal</h6>
                    <p class="text-muted mb-0">
                        Belum ada jadwal siswa bimbingan pada bulan ini.
                    </p>
                </div>
            @endforelse
        </div>

        @if($schedules->hasPages())
            <div class="card-footer bg-white border-0 px-3 px-md-4 py-3">
                {{ $schedules->links() }}
            </div>
        @endif
    </div>

    {{-- KETERANGAN --}}
    <div class="alert alert-info border-0 rounded-4 mt-4">
        <div class="d-flex gap-2">
            <i class="bi bi-info-circle-fill fs-5"></i>
            <div>
                <strong>Informasi pengelolaan jadwal</strong>
                <p class="mb-0 small">
                    Perubahan jadwal hanya berlaku untuk siswa yang berada
                    di bawah bimbingan Anda. Pilih status Libur jika siswa
                    tidak perlu masuk pada tanggal tersebut.
                </p>
            </div>
        </div>
    </div>

</div>

@endsection
