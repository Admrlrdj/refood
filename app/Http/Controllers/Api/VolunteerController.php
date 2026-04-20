<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use Illuminate\Http\Request;

class VolunteerController extends Controller
{
    // Mengambil semua data relawan yang sudah diverifikasi
    public function index()
    {
        $volunteers = Volunteer::where('is_verified', true)->get();
        return $this->sendResponse($volunteers, 'Data relawan berhasil diambil.');
    }

    // Mengambil detail 1 relawan berdasarkan ID
    public function show($id)
    {
        $volunteer = Volunteer::find($id);

        if (is_null($volunteer)) {
            return $this->sendError('Relawan tidak ditemukan.');
        }

        return $this->sendResponse($volunteer, 'Detail relawan berhasil diambil.');
    }

    // API untuk Flutter meng-update titik lokasi relawan secara realtime
    public function updateLocation(Request $request, $id)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $volunteer = Volunteer::find($id);

        if (is_null($volunteer)) {
            return $this->sendError('Relawan tidak ditemukan.');
        }

        $volunteer->last_latitude = (float) $request->latitude;
        $volunteer->last_longitude = (float) $request->longitude;
        $volunteer->save();

        return $this->sendResponse($volunteer, 'Lokasi berhasil diperbarui.');
    }
}
