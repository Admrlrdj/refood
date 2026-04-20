<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Receiver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ReceiverController extends Controller
{
    public function index()
    {
        $receivers = Receiver::orderBy('created_at', 'desc')->get();
        return view('admin.pages.receivers.index', compact('receivers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:receivers',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|max:20',
            'foundation_name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'capacity' => 'required|integer|min:1',
        ]);

        Receiver::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'foundation_name' => $request->foundation_name,
            'last_latitude' => (float) $request->latitude,
            'last_longitude' => (float) $request->longitude,
            'capacity' => $request->capacity,
            'is_verified' => true,
        ]);

        return redirect()->back()->with('success', 'Data Penerima (Yayasan) berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $receiver = Receiver::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:receivers,email,' . $id . ',_id',
            'phone' => 'required|string|max:20',
            'foundation_name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'capacity' => 'required|integer|min:1',
        ]);

        $receiver->name = $request->name;
        $receiver->email = $request->email;
        $receiver->phone = $request->phone;
        $receiver->foundation_name = $request->foundation_name;
        $receiver->last_latitude = (float) $request->latitude;
        $receiver->last_longitude = (float) $request->longitude;
        $receiver->capacity = $request->capacity;

        if ($request->filled('password')) {
            $receiver->password = Hash::make($request->password);
        }

        $receiver->save();

        return redirect()->back()->with('success', 'Data Penerima (Yayasan) berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Receiver::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data Penerima berhasil dihapus!');
    }

    public function verify($id)
    {
        $receiver = Receiver::findOrFail($id);
        $receiver->is_verified = true;
        $receiver->save();

        return back()->with('success', 'Akun Yayasan berhasil diverifikasi!');
    }

    public function reject($id)
    {
        $receiver = Receiver::findOrFail($id);
        $receiver->is_verified = false;
        $receiver->save();

        return back()->with('success', 'Status Yayasan diubah menjadi Belum Terverifikasi / Ditolak.');
    }
}
