<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // --- REGISTER RELAWAN ---
    public function registerVolunteer(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'username' => 'required|string|unique:volunteers',
            'password' => 'required|string|min:6',
            'phone' => 'required|string',
            'vehicle_type' => 'required|string',
        ]);

        $volunteer = Volunteer::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'vehicle_type' => $request->vehicle_type,
            'status' => 'aktif',
            'is_verified' => false, // Harus nunggu admin
            'last_latitude' => $request->latitude ?? null,
            'last_longitude' => $request->longitude ?? null,
        ]);

        return response()->json([
            'message' => 'Registrasi berhasil. Menunggu verifikasi admin.',
            'data' => $volunteer
        ], 201);
    }

    // --- LOGIN RELAWAN ---
    public function loginVolunteer(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $volunteer = Volunteer::where('username', $request->username)->first();

        // Cek apakah username ada dan password cocok
        if (!$volunteer || !Hash::check($request->password, $volunteer->password)) {
            return response()->json(['message' => 'Username atau Password salah!'], 401);
        }

        // Cek Verifikasi Admin (Logic yang kamu minta)
        if ($volunteer->is_verified == false) {
            return response()->json(['message' => 'Akun Anda belum diverifikasi oleh Admin.'], 403);
        }

        // Buat Token API (Sanctum)
        $token = $volunteer->createToken('volunteer-token')->plainTextToken;

        return response()->json([
            'message' => 'Login Berhasil',
            'token' => $token,
            'data' => $volunteer
        ], 200);
    }

    public function getVolunteers()
    {
        // Mengambil semua data dari collection volunteers
        $volunteers = Volunteer::all();

        return response()->json([
            'message' => 'Berhasil mengambil data relawan',
            'data' => $volunteers
        ], 200);
    }
}
