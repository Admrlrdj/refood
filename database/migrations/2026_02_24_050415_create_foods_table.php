<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('mongodb')->create('foods', function (Blueprint $table) {
            $table->id();
            // Referensi ke ObjectId donors (Tanpa cascade constraint)
            $table->string('donor_id')->index();

            $table->string('name');
            $table->text('description');
            $table->integer('portion');
            $table->string('pickup_address');
            $table->double('latitude');
            $table->double('longitude');
            $table->string('status');
            $table->timestamp('expired_at')->nullable();
            $table->string('photo_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('mongodb')->dropIfExists('foods');
    }
};
