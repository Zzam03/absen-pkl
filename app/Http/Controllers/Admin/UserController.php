<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Daftar siswa dan pembimbing, dengan filter ?role= dan pencarian ?q=
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['student', 'pembimbing'])->orderBy('name');

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
            'name'        => 'required|string|max:100',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|min:8',
            'role'        => 'required|in:student,pembimbing',
            'phone'       => 'nullable|string|max:20',
            'school_name' => 'required_if:role,student|nullable|string|max:100',
        ]);

        $data['password'] = Hash::make($data['password']);
        User::create($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan!');
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
            'name'        => 'required|string|max:100',
            'email'       => 'required|email|unique:users,email,'.$user->id,
            'password'    => 'nullable|min:8',
            'phone'       => 'nullable|string|max:20',
            'school_name' => 'nullable|string|max:100',
        ]);

        // Password kosong = tidak diganti
        if ($request->filled('password')) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // Checkbox: tidak dicentang = tidak terkirim = false
        $data['is_active'] = $request->boolean('is_active');

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Data user diperbarui!');
    }

    public function destroy(User $user)
    {
        abort_if($user->role === 'admin', 403);

        if ($user->placements()->exists() || $user->supervisedPlacements()->exists()) {
            return back()->with('error', 'User ini punya data penempatan. Nonaktifkan saja lewat menu Edit.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User dihapus!');
    }
}