<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::orderBy('name');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->q.'%')
                    ->orWhere('email', 'like', '%'.$request->q.'%');
            });
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
            'role' => [
                'required',
                Rule::in([
                    'student',
                    'pembimbing_kantor',
                    'pembimbing_sekolah',
                ]),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'school_name' => [
                'required_if:role,student',
                'nullable',
                'string',
                'max:100',
            ],
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = true;

        User::create($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        // Akun Admin tidak boleh diedit melalui menu ini
        abort_if($user->role === 'admin', 403);

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Lindungi akun Admin
        abort_if($user->role === 'admin', 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'min:8'],
            'phone' => ['nullable', 'string', 'max:20'],
            'school_name' => ['nullable', 'string', 'max:100'],
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['is_active'] = $request->boolean('is_active');

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Data user diperbarui!');
    }

    public function destroy(User $user)
    {
        // Lindungi akun Admin
        abort_if($user->role === 'admin', 403);

        // Cek apakah pengguna memiliki data penempatan
        if (
            $user->placements()->exists() ||
            $user->supervisedPlacements()->exists()
        ) {
            return back()->with(
                'error',
                'Pengguna memiliki data penempatan. Nonaktifkan akun melalui menu Edit.'
            );
        }

        // Lindungi riwayat absensi
        if ($user->attendances()->exists()) {
            return back()->with(
                'error',
                'Pengguna memiliki riwayat absensi sehingga tidak dapat dihapus. Nonaktifkan akun melalui menu Edit.'
            );
        }

        // Lindungi riwayat jurnal
        if ($user->journals()->exists()) {
            return back()->with(
                'error',
                'Pengguna memiliki riwayat jurnal sehingga tidak dapat dihapus. Nonaktifkan akun melalui menu Edit.'
            );
        }

        // Hapus pengguna jika tidak memiliki data terkait
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus!');
    }
}
