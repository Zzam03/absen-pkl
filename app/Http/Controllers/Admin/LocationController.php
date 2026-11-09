<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    private function rules(): array
    {
        return [
            'name'         => 'required|string|max:100',
            'address'      => 'nullable|string|max:255',
            'latitude'     => 'required|numeric|between:-90,90',
            'longitude'    => 'required|numeric|between:-180,180',
            'radius_meter' => 'required|integer|min:10|max:5000',
        ];
    }

    public function index()
    {
        $locations = Location::withCount('placements')->orderBy('name')->get();

        return view('admin.locations.index', compact('locations'));
    }

    public function create()
    {
        return view('admin.locations.create');
    }

    public function store(Request $request)
    {
        Location::create($request->validate($this->rules()));

        return redirect()->route('admin.locations.index')->with('success', 'Lokasi ditambahkan!');
    }

    public function edit(Location $location)
    {
        return view('admin.locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        $location->update($request->validate($this->rules()));

        return redirect()->route('admin.locations.index')->with('success', 'Lokasi diperbarui!');
    }

    public function destroy(Location $location)
    {
        if ($location->placements()->exists()) {
            return back()->with('error', 'Lokasi masih dipakai penempatan, tidak bisa dihapus.');
        }

        $location->delete();

        return redirect()->route('admin.locations.index')->with('success', 'Lokasi dihapus!');
    }
}