
@extends('layouts.main')

@section('content')

<div class="container-fluid px-2 px-md-3">

    <div class="mb-4">
        <a href="{{ route('admin.users.index') }}"
           class="text-decoration-none text-muted small">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Data Pengguna
        </a>

        <h3 class="fw-bold mt-3 mb-1">Edit Pengguna</h3>
        <p class="text-muted mb-0">
            Perbarui informasi akun {{ $user->name }}.
        </p>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">

            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <h5 class="fw-bold mb-4">
                    <i class="bi bi-person-gear text-primary me-2"></i>
                    Informasi Pengguna
                </h5>

                <div class="row g-3">

                    <div class="col-12">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text"
                               name="name"
                               value="{{ old('name', $user->name) }}"
                               class="form-control @error('name') is-invalid @enderror"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email"
                               name="email"
                               value="{{ old('email', $user->email) }}"
                               class="form-control @error('email') is-invalid @enderror"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">Nomor Telepon</label>
                        <input type="text"
                               name="phone"
                               value="{{ old('phone', $user->phone) }}"
                               class="form-control @error('phone') is-invalid @enderror">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Nama Sekolah</label>
                        <input type="text"
                               name="school_name"
                               value="{{ old('school_name', $user->school_name) }}"
                               class="form-control @error('school_name') is-invalid @enderror">
                        @error('school_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            Password Baru
                        </label>
                        <input type="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Kosongkan jika tidak ingin mengganti password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            Minimal 8 karakter jika ingin mengganti password.
                        </small>
                    </div>

                </div>

                <hr class="my-4">

                <div class="p-3 bg-light rounded-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input"
                               type="checkbox"
                               role="switch"
                               name="is_active"
                               value="1"
                               id="is_active"
                               @checked(old('is_active', $user->is_active))>

                        <label class="form-check-label fw-semibold"
                               for="is_active">
                            Akun Aktif
                        </label>
                    </div>

                    <small class="text-muted">
                        Matikan jika pengguna tidak lagi menggunakan akun ini.
                    </small>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.users.index') }}"
                       class="btn btn-light border rounded-3 px-4">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary rounded-3 px-4">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection
