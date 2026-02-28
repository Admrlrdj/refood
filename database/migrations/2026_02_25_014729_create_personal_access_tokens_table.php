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
        Schema::connection('mongodb')->create('personal_access_tokens', function (Blueprint $table) {
            $table->id();

            // Pengganti morphs('tokenable') khusus untuk MongoDB
            $table->string('tokenable_id');
            $table->string('tokenable_type');
            $table->index(['tokenable_id', 'tokenable_type']);

            $table->string('name');
            $table->string('token')->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mongodb')->dropIfExists('personal_access_tokens');
    }
};
