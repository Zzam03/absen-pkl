<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {
    public function index() {
        $users = User::whereIn('role', ['student', 'pembimbing_kantor', 'pembimbing_sekolah'])->get();
        return view('admin.users', compact('users'));
    }
    public function store(Request $request) {
        $request->validate(['name' => 'required', 'email' => 'required|email|unique:users', 'role' => 'required', 'password' => 'required|min:6']);
        User::create([
            'name' => $request->name, 'email' => $request->email, 'role' => $request->role,
            'password' => Hash::make($request->password), 'is_active' => true
        ]);
        return back()->with('success', 'User berhasil ditambahkan!');
    }
    public function destroy(User $user) {
        $user->delete();
        return back()->with('success', 'User berhasil dihapus!');
    }
}