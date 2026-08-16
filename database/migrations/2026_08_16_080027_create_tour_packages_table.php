<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tour_packages', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique();

            $table->string('tag')->nullable();

            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();

            $table->decimal('price', 15, 2)->default(0);

            $table->unsignedInteger('duration_days')->default(1);
            $table->unsignedInteger('duration_nights')->default(0);

            $table->string('image')->nullable();

            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_packages');
    }
};