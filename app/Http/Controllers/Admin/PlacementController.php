<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Placement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PlacementController extends Controller
{
    private function rules(bool $edit = false): array
    {
        $rules = [
            'student_id'    => ['required', Rule::exists('users', 'id')->where('role', 'student')],
            'pembimbing_id' => ['required', Rule::exists('users', 'id')->where('role', 'pembimbing')],
            'location_id'   => ['required', 'exists:locations,id'],
            'start_date'    => ['required', 'date'],
            'end_date'      => ['required', 'date', 'after_or_equal:start_date'],
        ];

        if ($edit) {
            $rules['status'] = ['required', 'in:active,finished,cancelled'];
        }

        return $rules;
    }

    // Data pilihan untuk dropdown di form
    private function dropdowns(): array
    {
        return [
            'students'    => User::where('role', 'student')->where('is_active', true)->orderBy('name')->get(),
            'pembimbings' => User::where('role', 'pembimbing')->where('is_active', true)->orderBy('name')->get(),
            'locations'   => Location::orderBy('name')->get(),
        ];
    }

    public function index()
    {
        $placements = Placement::with(['student', 'pembimbing', 'location', 'score'])
            ->orderByDesc('start_date')
            ->paginate(15);

        return view('admin.placements.index', compact('placements'));
    }

    public function create()
    {
        return view('admin.placements.create', $this->dropdowns());
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        $sudahAktif = Placement::where('student_id', $data['student_id'])
            ->where('status', 'active')->exists();

        if ($sudahAktif) {
            return back()->withInput()->with('error', 'Siswa ini sudah punya penempatan aktif.');
        }

        Placement::create($data + ['status' => 'active']);

        return redirect()->route('admin.placements.index')->with('success', 'Penempatan dibuat!');
    }

    public function edit(Placement $placement)
    {
        return view('admin.placements.edit', $this->dropdowns() + ['placement' => $placement]);
    }

    public function update(Request $request, Placement $placement)
    {
        $data = $request->validate($this->rules(true));

        $bentrok = $data['status'] === 'active'
            && Placement::where('student_id', $data['student_id'])
                ->where('status', 'active')
                ->where('id', '!=', $placement->id)
                ->exists();

        if ($bentrok) {
            return back()->withInput()->with('error', 'Siswa ini sudah punya penempatan aktif lain.');
        }

        $placement->update($data);

        return redirect()->route('admin.placements.index')->with('success', 'Penempatan diperbarui!');
    }

    public function destroy(Placement $placement)
    {
        if ($placement->attendances()->exists() || $placement->journals()->exists()) {
            return back()->with('error', 'Sudah ada data absen atau jurnal. Ubah statusnya menjadi cancelled lewat Edit.');
        }

        $placement->delete();

        return redirect()->route('admin.placements.index')->with('success', 'Penempatan dihapus!');
    }
}