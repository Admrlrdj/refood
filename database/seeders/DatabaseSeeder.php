<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Volunteer;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Relawan yang SUDAH diverifikasi (BISA LOGIN)
        Volunteer::create([
            'name' => 'Radja Admiral',
            'username' => 'radja_kurir',
            'password' => Hash::make('password123'), // Passwordnya: password123
            'phone' => '081234567890',
            'vehicle_type' => 'Honda Beat',
            'status' => 'aktif',
            'is_verified' => true, // <-- Penting! Ini yg bikin bisa tembus login
            'last_latitude' => -6.597629,
            'last_longitude' => 106.799566,
        ]);

        // 2. Akun Relawan yang BELUM diverifikasi (GAGAL LOGIN)
        Volunteer::create([
            'name' => 'Oji Kurir',
            'username' => 'oji_kurir',
            'password' => Hash::make('password123'),
            'phone' => '089876543210',
            'vehicle_type' => 'Yamaha NMAX',
            'status' => 'aktif',
            'is_verified' => false, // <-- Nanti muncul notif belum diverifikasi
            'last_latitude' => -6.600000,
            'last_longitude' => 106.800000,
        ]);

        echo "✅ Data Relawan berhasil ditambahkan ke MongoDB!\n";
    }
}
