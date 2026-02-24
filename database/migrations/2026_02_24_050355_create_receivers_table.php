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
        Schema::create('receivers', function (Blueprint $table) {
            $table->id();
            $table->string('organization_name');
            $table->string('username')->unique(); // Buat Login
            $table->string('password');           // Buat Login
            $table->string('contact_person');
            $table->string('phone');
            $table->string('address');
            $table->integer('capacity');
            $table->boolean('is_verified')->default(false); // Verifikasi Admin
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receivers');
    }
};
