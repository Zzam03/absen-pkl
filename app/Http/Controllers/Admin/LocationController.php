<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;

class LocationController extends Controller {
    public function index() {
        $locations = Location::all();
        return view('admin.locations', compact('locations'));
    }
    public function store(Request $request) {
        $request->validate(['name' => 'required', 'latitude' => 'required', 'longitude' => 'required', 'radius_meter' => 'required|integer']);
        Location::create($request->all());
        return back()->with('success', 'Lokasi berhasil ditambahkan!');
    }
    public function destroy(Location $location) {
        $location->delete();
        return back()->with('success', 'Lokasi berhasil dihapus!');
    }
}