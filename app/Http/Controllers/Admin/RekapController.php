<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with('user')->orderByDesc('tanggal');

        if ($request->filled('dari')) {
            $query->whereDate('tanggal', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal', '<=', $request->sampai);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $rekap   = $query->paginate(15)->withQueryString();
        $peserta = User::where('role', 'peserta')->orderBy('name')->get();

        return view('admin.rekap', compact('rekap', 'peserta'));
    }
}