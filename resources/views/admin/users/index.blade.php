
@extends('layouts.main')

@section('content')

<div class="container-fluid px-2 px-md-3">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Data Pengguna</h3>
            <p class="text-muted mb-0">
                Kelola data siswa dan pembimbing PKL.
            </p>
        </div>

        <a href="{{ route('admin.users.create') }}"
           class="btn btn-primary rounded-3 px-3 mt-3 mt-md-0">
            <i class="bi bi-plus-lg me-2"></i>Tambah Pengguna
        </a>
    </div>

    {{-- Statistik --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 bg-primary bg-opacity-10 text-primary p-3">
                    <i class="bi bi-people-fill fs-4"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0">{{ $users->total() }}</h4>
                    <p class="text-muted small mb-0">Total hasil pengguna</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter dan Pencarian --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3 p-md-4">
            <form action="{{ route('admin.users.index') }}" method="GET">
                <div class="row g-3">

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold small">
                            Cari Pengguna
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text"
                                   name="q"
                                   value="{{ request('q') }}"
                                   class="form-control bg-light border-start-0"
                                   placeholder="Cari nama atau email...">
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label fw-semibold small">
                            Filter Role
                        </label>
                        <select name="role" class="form-select bg-light">
                            <option value="">Semua Pengguna</option>
                            <option value="student" @selected(request('role') === 'student')>
                                Siswa
                            </option>
                            <option value="pembimbing" @selected(request('role') === 'pembimbing')>
                                Pembimbing
                            </option>
                        </select>
                    </div>

                    <div class="col-12 col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            Terapkan
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Pengguna --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-3 p-md-4">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Daftar Pengguna</h5>
                <span class="text-muted small">
                    {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }}
                    dari {{ $users->total() }} data
                </span>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3">Pengguna</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td class="py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold"
                                             style="width: 42px; height: 42px;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold">
                                                {{ $user->name }}
                                            </div>
                                            <small class="text-muted">
                                                {{ $user->phone ?: 'Nomor telepon belum diisi' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                <td>{{ $user->email }}</td>

                                <td>
                                    @if($user->role === 'student')
                                        <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-3 py-2">
                                            Siswa
                                        </span>
                                    @else
                                        <span class="badge rounded-pill bg-info bg-opacity-10 text-info px-3 py-2">
                                            Pembimbing
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if($user->is_active)
                                        <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                <td class="text-end">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="btn btn-sm btn-light border rounded-3"
                                       title="Edit pengguna">
                                        <i class="bi bi-pencil-square text-primary"></i>
                                    </a>

                                    <form action="{{ route('admin.users.destroy', $user) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-light border rounded-3"
                                                title="Hapus pengguna">
                                            <i class="bi bi-trash text-danger"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="bi bi-people text-muted fs-1"></i>
                                    <p class="text-muted mt-2 mb-0">
                                        Tidak ada data pengguna ditemukan.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $users->links() }}
            </div>

        </div>
    </div>

</div>

@endsection
