
@extends('layouts.main')

@section('content')

<div class="container-fluid px-2 px-md-3">

    <div class="mb-4">
        <a href="{{ route('admin.users.index') }}"
           class="text-decoration-none text-muted small">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Data Pengguna
        </a>

        <h3 class="fw-bold mt-3 mb-1">Tambah Pengguna</h3>
        <p class="text-muted mb-0">
            Masukkan informasi akun siswa atau pembimbing.
        </p>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <h5 class="fw-bold mb-4">
                    <i class="bi bi-person-plus text-primary me-2"></i>
                    Informasi Pengguna
                </h5>

                <div class="row g-3">

                    <div class="col-12">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="Masukkan nama lengkap"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="contoh@email.com"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">Nomor Telepon</label>
                        <input type="text"
                               name="phone"
                               value="{{ old('phone') }}"
                               class="form-control @error('phone') is-invalid @enderror"
                               placeholder="08xxxxxxxxxx">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">Role Pengguna</label>
                        <select name="role"
                                id="role"
                                class="form-select @error('role') is-invalid @enderror"
                                required>
                            <option value="">Pilih role</option>
                            <option value="student" @selected(old('role') === 'student')>
                                Siswa
                            </option>
                            <option value="pembimbing" @selected(old('role') === 'pembimbing')>
                                Pembimbing
                            </option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Minimal 8 karakter"
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12" id="schoolField">
                        <label class="form-label fw-semibold">Nama Sekolah</label>
                        <input type="text"
                               name="school_name"
                               value="{{ old('school_name') }}"
                               class="form-control @error('school_name') is-invalid @enderror"
                               placeholder="Masukkan nama sekolah">
                        @error('school_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.users.index') }}"
                       class="btn btn-light border rounded-3 px-4">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary rounded-3 px-4">
                        <i class="bi bi-check-lg me-1"></i> Simpan Pengguna
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>

@push('scripts')
<script>
    const roleSelect = document.getElementById('role');
    const schoolField = document.getElementById('schoolField');

    function toggleSchoolField() {
        schoolField.style.display =
            roleSelect.value === 'student' ? 'block' : 'none';
    }

    roleSelect.addEventListener('change', toggleSchoolField);
    toggleSchoolField();
</script>
@endpush

@endsection
