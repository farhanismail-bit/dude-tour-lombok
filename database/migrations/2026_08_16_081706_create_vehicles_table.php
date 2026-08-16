<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();

            // Informasi kendaraan
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type');

            // Kapasitas
            $table->unsignedTinyInteger('passenger_capacity')->default(1);
            $table->unsignedTinyInteger('luggage_capacity')->default(0);

            // Harga
            $table->decimal('price_per_day', 15, 2)->default(0);

            // Informasi tambahan
            $table->text('description')->nullable();
            $table->json('facilities')->nullable();

            // Media
            $table->string('image')->nullable();

            // Status
            $table->boolean('is_available')->default(true);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};