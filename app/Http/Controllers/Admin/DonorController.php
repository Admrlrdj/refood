<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DonorController extends Controller
{
    public function index()
    {
        $donors = Donor::orderBy('created_at', 'desc')->get();
        return view('admin.pages.donors.index', compact('donors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:donors',
            'password' => 'required|string|min:6',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'restaurant_name' => 'required|string|max:255',
        ]);

        Donor::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'last_latitude' => (float) $request->latitude,
            'last_longitude' => (float) $request->longitude,
            'restaurant_name' => $request->restaurant_name,
            'is_verified' => true,
        ]);

        return redirect()->back()->with('success', 'Data Donatur berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $donor = Donor::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:donors,email,' . $id . ',_id',
            'last_latitude' => (float) $request->latitude,
            'last_longitude' => (float) $request->longitude,
            'restaurant_name' => 'required|string|max:255',
        ]);

        $donor->name = $request->name;
        $donor->phone = $request->phone;
        $donor->email = $request->email;
        $donor->last_latitude = (float) $request->latitude;
        $donor->last_longitude = (float) $request->longitude;
        $donor->restaurant_name = $request->restaurant_name;
        $donor->save();

        return redirect()->back()->with('success', 'Data Donatur berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $donor = Donor::findOrFail($id);
        $donor->delete();

        return redirect()->back()->with('success', 'Data Donatur berhasil dihapus!');
    }

    public function verify($id)
    {
        $donor = Donor::findOrFail($id);
        $donor->is_verified = true;
        $donor->save();

        return back()->with('success', 'Akun Donatur berhasil diverifikasi!');
    }

    public function reject($id)
    {
        $donor = Donor::findOrFail($id);
        $donor->is_verified = false;
        $donor->save();

        return back()->with('success', 'Status Donatur diubah menjadi Belum Terverifikasi / Ditolak.');
    }
}
