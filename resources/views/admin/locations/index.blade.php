@extends('layouts.main')
@section('title', 'Data Lokasi')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>Data Lokasi</h4>
    <a href="{{ route('admin.locations.create') }}" class="btn btn-primary">+ Tambah Lokasi</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead class="table-primary">
                <tr><th>Nama</th><th>Alamat</th><th>Koordinat</th><th>Radius</th><th>Dipakai</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($locations as $l)
                    <tr>
                        <td>{{ $l->name }}</td>
                        <td>{{ $l->address ?? '-' }}</td>
                        <td>{{ $l->latitude }}, {{ $l->longitude }}</td>
                        <td>{{ $l->radius_meter }} m</td>
                        <td>{{ $l->placements_count }} penempatan</td>
                        <td>
                            <a href="{{ route('admin.locations.edit', $l) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('admin.locations.destroy', $l) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin hapus lokasi ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">Belum ada lokasi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection