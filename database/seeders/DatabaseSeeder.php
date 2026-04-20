<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// use App\Models\Volunteer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@refood.com',
            'password' => Hash::make('admin123'),
        ]);

        // Volunteer::create([
        //     'name' => 'Radja Admiral',
        //     'username' => 'radja_kurir',
        //     'password' => Hash::make('password123'),
        //     'phone' => '081234567890',
        //     'vehicle_type' => 'Honda Beat',
        //     'status' => 'aktif',
        //     'is_verified' => true,
        //     'last_latitude' => -6.597629,
        //     'last_longitude' => 106.799566,
        // ]);

        // Vol  unteer::create([
        //     'name' => 'Oji Kurir',
        //     'username' => 'oji_kurir',
        //     'password' => Hash::make('password123'),
        //     'phone' => '089876543210',
        //     'vehicle_type' => 'Yamaha NMAX',
        //     'status' => 'aktif',
        //     'is_verified' => false,
        //     'last_latitude' => -6.600000,
        //     'last_longitude' => 106.800000,
        // ]);

        echo "✅ Data User berhasil ditambahkan ke MongoDB!\n";
    }
}
