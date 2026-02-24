<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('mongodb')->create('deliveries', function (Blueprint $table) {
            $table->id();
            // Referensi ke ObjectId (Tanpa cascade constraint)
            $table->string('food_id')->index();
            $table->string('receiver_id')->index();
            $table->string('volunteer_id')->index();

            $table->timestamp('pickup_time')->nullable();
            $table->timestamp('delivered_time')->nullable();
            $table->string('status');
            $table->string('proof_photo')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('mongodb')->dropIfExists('deliveries');
    }
};
