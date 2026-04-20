<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use App\Models\Donor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function registerVolunteer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'username' => 'required|string|unique:volunteers',
            'password' => 'required|string|min:6',
            'phone' => 'required|string',
            'vehicle_type' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal, periksa kembali data Anda.',
                'errors' => $validator->errors()
            ], 422);
        }

        $volunteer = Volunteer::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'vehicle_type' => $request->vehicle_type,
            'status' => 'aktif',
            'is_verified' => false,
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

        if (!$volunteer || !Hash::check($request->password, $volunteer->password)) {
            throw ValidationException::withMessages([
                'username' => ['Username atau password salah.'],
            ]);
        }

        if ($volunteer->is_verified == false) {
            return response()->json(['message' => 'Akun Anda belum diverifikasi oleh Admin.'], 403);
        }

        $token = $volunteer->createToken('volunteer-token')->plainTextToken;

        return response()->json([
            'message' => 'Successfully logged in',
            'token' => $token,
            'user' => $volunteer
        ]);
    }

    public function loginDonor(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $donor = Donor::where('email', $request->email)->first();

        // Cek email, password, dan status verifikasi
        if (!$donor || !Hash::check($request->password, $donor->password)) {
            return $this->sendError('Email atau Password salah.', [], 401);
        }

        if (!$donor->is_verified) {
            return $this->sendError('Akun belum diverifikasi oleh Admin.', [], 403);
        }

        // Buat Token Sanctum
        $token = $donor->createToken('donor_auth_token')->plainTextToken;

        $data = [
            'donor' => $donor,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ];

        return $this->sendResponse($data, 'Login berhasil.');
    }
}
