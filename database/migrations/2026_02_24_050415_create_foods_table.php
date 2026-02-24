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
        Schema::create('foods', function (Blueprint $table) {
            $table->id();
            // FK ke tabel donors
            $table->foreignId('donor_id')->constrained('donors')->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->integer('portion');
            $table->string('pickup_address');
            $table->double('latitude');
            $table->double('longitude');
            $table->string('status'); // misal: 'tersedia', 'diambil', 'expired'
            $table->timestamp('expired_at')->nullable();
            $table->string('photo_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foods');
    }
};
