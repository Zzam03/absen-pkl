@extends('layouts.main')
@section('title', 'Profil Saya - PKL Connect')
@section('page-heading', 'Profil Saya')

@section('content')
@php
    $tanggalBergabung = $user->created_at
        ? \Illuminate\Support\Carbon::parse($user->created_at)->translatedFormat('M Y')
        : '-';
@endphp

<div class="container-fluid py-3">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-8">

            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="p-4 text-white" style="background: linear-gradient(120deg, #2563eb, #4338ca);">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <img
                            src="{{ $user->photo ? asset('storage/'.$user->photo) : 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}"
                            alt="Foto profil"
                            class="rounded-circle border border-3 border-white"
                            style="width: 82px; height: 82px; object-fit: cover;"
                        >
                        <div>
                            <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                            <div class="text-white-50 text-capitalize">
                                {{ str_replace('_', ' ', $user->role) }}
                            </div>
                            <small class="text-white-50">
                                Bergabung {{ $tanggalBergabung }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Periksa kembali data berikut:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('status') === 'profile-updated')
                <div class="alert alert-success">
                    Profil berhasil diperbarui!
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Informasi Pribadi</h5>
                        <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </div>

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="{{ old('name', $user->name) }}"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="{{ old('email', $user->email) }}"
                                required
                            >
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Nomor HP</label>
                            <input
                                type="text"
                                name="phone"
                                class="form-control"
                                value="{{ old('phone', $user->phone) }}"
                                placeholder="Masukkan nomor HP"
                            >
                        </div>

                        <hr>

                        <h6 class="fw-bold mb-1">Ganti Password</h6>
                        <p class="text-muted small">
                            Kosongkan jika tidak ingin mengganti password.
                        </p>

                        <div class="mb-3">
                            <label class="form-label">Password Baru</label>
                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                minlength="8"
                                autocomplete="new-password"
                                placeholder="Minimal 8 karakter"
                            >
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                                autocomplete="new-password"
                                placeholder="Ulangi password baru"
                            >
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
