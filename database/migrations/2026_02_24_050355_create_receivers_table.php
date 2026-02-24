<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('mongodb')->create('receivers', function (Blueprint $table) {
            $table->id();
            $table->string('organization_name');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('contact_person');
            $table->string('phone');
            $table->string('address');
            $table->integer('capacity');
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('mongodb')->dropIfExists('receivers');
    }
};
