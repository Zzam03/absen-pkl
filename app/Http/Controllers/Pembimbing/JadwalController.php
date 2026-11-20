<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JadwalController extends Controller
{
    // MENAMPILKAN JADWAL SISWA BIMBINGAN AZMI
    public function index(Request $request)
    {
        $request->validate([
            'month' => 'nullable|date_format:Y-m',
        ]);

        $month = $request->input('month', now()->format('Y-m'));

        $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $schedules = Schedule::with([
            'placement.student',
            'placement.location',
        ])
            ->whereBetween('date', [
                $startDate->format('Y-m-d'),
                $endDate->format('Y-m-d'),
            ])
            ->whereHas('placement', function ($query) {
                $query->where('pembimbing_id', auth()->id())
                    ->where('status', 'active');
            })
            ->orderBy('date')
            ->orderBy('start_time')
            ->paginate(20)
            ->withQueryString();

        return view('pembimbing.jadwal.index', compact(
            'schedules',
            'month'
        ));
    }

    // MEMPERBARUI JADWAL SISWA BIMBINGAN
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'status' => 'required|in:aktif,libur',
            'activity' => 'nullable|string|max:500',
        ]);

        if (
            $data['status'] === 'aktif' &&
            $data['end_time'] <= $data['start_time']
        ) {
            return back()
                ->withErrors([
                    'end_time' => 'Jam pulang harus lebih besar dari jam masuk.',
                ])
                ->withInput();
        }

        // PASTIKAN JADWAL MILIK SISWA BIMBINGAN AZMI
        $schedule = Schedule::whereHas('placement', function ($query) {
            $query->where('pembimbing_id', auth()->id())
                ->where('status', 'active');
        })->findOrFail($id);

        $schedule->update($data);

        return redirect()
            ->route('pembimbing.jadwal.index', [
                'month' => Carbon::parse($schedule->date)->format('Y-m'),
            ])
            ->with('success', 'Jadwal siswa berhasil diperbarui.');
    }
}
