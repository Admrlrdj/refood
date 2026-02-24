<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('volunteers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique(); // Buat Login
            $table->string('password');           // Buat Login
            $table->string('phone');
            $table->string('vehicle_type');
            $table->double('last_latitude')->nullable();  // Buat fitur kurir terdekat
            $table->double('last_longitude')->nullable(); // Buat fitur kurir terdekat
            $table->string('status');
            $table->boolean('is_verified')->default(false); // Verifikasi Admin
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteers');
    }
};
