<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Placement; // Ditambahkan untuk fitur penempatan
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // Ditambahkan untuk transaksi
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

    // === FUNGSI STORE BIASA (Dari kode asli Anda) ===
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

    // === FUNGSI BARU: SIMPAN SISWA + PENEMPATAN (TRANSAKSI) ===
    public function storeWithPlacement(Request $request)
    {
        $request->validate([
            // Validasi User
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
            'school_name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            
            // Validasi Penempatan
            'location_id' => ['required', 'exists:locations,id'],
            'pembimbing_id' => ['required', 'exists:users,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 1. Buat Akun Siswa
                $student = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => 'student',
                    'phone' => $request->phone,
                    'school_name' => $request->school_name,
                    'is_active' => true
                ]);

                // 2. Buat Penempatan
                Placement::create([
                    'student_id' => $student->id,
                    'location_id' => $request->location_id,
                    'pembimbing_id' => $request->pembimbing_id,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'status' => 'active'
                ]);
            });

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'Siswa dan Penempatan PKL berhasil dibuat!');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal membuat data: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(User $user)
    {
        abort_if($user->role === 'admin', 403);

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
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
        abort_if($user->role === 'admin', 403);

        if (
            $user->placements()->exists() ||
            $user->supervisedPlacements()->exists()
        ) {
            return back()->with(
                'error',
                'Pengguna memiliki data penempatan. Nonaktifkan akun melalui menu Edit.'
            );
        }

        if ($user->attendances()->exists()) {
            return back()->with(
                'error',
                'Pengguna memiliki riwayat absensi sehingga tidak dapat dihapus. Nonaktifkan akun melalui menu Edit.'
            );
        }

        if ($user->journals()->exists()) {
            return back()->with(
                'error',
                'Pengguna memiliki riwayat jurnal sehingga tidak dapat dihapus. Nonaktifkan akun melalui menu Edit.'
            );
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus!');
    }
}