<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PesertaController extends Controller
{
    // Daftar peserta
    public function index()
    {
        $peserta = User::where('role', 'peserta')->orderBy('name')->get();
        return view('admin.peserta.index', compact('peserta'));
    }

    // Form tambah peserta
    public function create()
    {
        return view('admin.peserta.create');
    }

    // Simpan peserta baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:100',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|min:8',
            'asal_sekolah' => 'nullable|string|max:100',
            'no_hp'        => 'nullable|string|max:20',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['role']     = 'peserta';

        User::create($data);

        return redirect()->route('admin.peserta.index')
            ->with('success', 'Peserta berhasil ditambahkan!');
    }

    // Form edit peserta
    public function edit(User $peserta)
    {
        return view('admin.peserta.edit', compact('peserta'));
    }

    // Simpan perubahan
    public function update(Request $request, User $peserta)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:100',
            'email'        => 'required|email|unique:users,email,' . $peserta->id,
            'password'     => 'nullable|min:8',
            'asal_sekolah' => 'nullable|string|max:100',
            'no_hp'        => 'nullable|string|max:20',
        ]);

        // Kalau password dikosongkan, jangan diganti
        if ($request->filled('password')) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $peserta->update($data);

        return redirect()->route('admin.peserta.index')
            ->with('success', 'Data peserta diperbarui!');
    }

    // Hapus peserta
    public function destroy(User $peserta)
    {
        // Pengaman: akun admin tidak boleh dihapus lewat fitur ini
        if ($peserta->role === 'admin') {
            return back()->with('error', 'Akun admin tidak bisa dihapus!');
        }

        $peserta->delete();

        return redirect()->route('admin.peserta.index')
            ->with('success', 'Peserta dihapus!');
    }
}