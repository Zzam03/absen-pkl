<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Placement;
use App\Models\User;
use App\Models\Location;

class PlacementController extends Controller {
    public function index() {
        $placements = Placement::with(['student', 'mentor', 'location'])->get();
        $students = User::where('role', 'student')->get();
        $mentors = User::whereIn('role', ['pembimbing_kantor', 'pembimbing_sekolah'])->get();
        $locations = Location::all();
        return view('admin.placements', compact('placements', 'students', 'mentors', 'locations'));
    }
    public function store(Request $request) {
        $request->validate(['student_id' => 'required', 'pembimbing_id' => 'required', 'location_id' => 'required', 'start_date' => 'required|date']);
        Placement::create(array_merge($request->all(), ['status' => 'active']));
        return back()->with('success', 'Penempatan berhasil dibuat!');
    }
}