<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('mongodb')->create('volunteers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('phone');
            $table->string('vehicle_type');
            $table->double('last_latitude')->nullable();
            $table->double('last_longitude')->nullable();
            $table->string('status');
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('mongodb')->dropIfExists('volunteers');
    }
};
