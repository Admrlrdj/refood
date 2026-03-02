<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class VolunteerController extends Controller
{
    public function index()
    {
        $volunteers = Volunteer::orderBy('created_at', 'desc')->get();
        return view('admin.pages.volunteers.index', compact('volunteers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:volunteers',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|max:20',
            'vehicle_type' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        Volunteer::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'vehicle_type' => $request->vehicle_type,
            'last_latitude' => (float) $request->latitude,
            'last_longitude' => (float) $request->longitude,
            'status' => 'aktif',
            'is_verified' => true,
        ]);

        return redirect()->back()->with('success', 'Relawan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $volunteer = Volunteer::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:volunteers,username,' . $id . ',_id',
            'phone' => 'required|string|max:20',
            'vehicle_type' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $volunteer->name = $request->name;
        $volunteer->username = $request->username;
        $volunteer->phone = $request->phone;
        $volunteer->vehicle_type = $request->vehicle_type;
        $volunteer->last_latitude = (float) $request->latitude;
        $volunteer->last_longitude = (float) $request->longitude;

        if ($request->filled('password')) {
            $volunteer->password = Hash::make($request->password);
        }

        $volunteer->save();

        return redirect()->back()->with('success', 'Data relawan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $volunteer = Volunteer::findOrFail($id);
        $volunteer->delete();

        return redirect()->back()->with('success', 'Data relawan berhasil dihapus secara permanen!');
    }

    public function verify($id)
    {
        $volunteer = Volunteer::find($id);
        if ($volunteer) {
            $volunteer->is_verified = true;
            $volunteer->save();
            return back()->with('success', 'Akun relawan ' . $volunteer->name . ' berhasil diverifikasi!');
        }
        return back()->with('error', 'Data tidak ditemukan!');
    }

    public function reject($id)
    {
        $volunteer = Volunteer::find($id);
        if ($volunteer) {
            $volunteer->delete();
            return back()->with('success', 'Pendaftaran relawan ditolak & dihapus.');
        }
        return back()->with('error', 'Data tidak ditemukan!');
    }
}
