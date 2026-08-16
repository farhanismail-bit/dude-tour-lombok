<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // Nomor booking untuk customer/admin
            $table->string('booking_code')->unique();

            // Customer
            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnDelete();

            // Pilihan layanan
            $table->foreignId('service_id')
                ->nullable()
                ->constrained('services')
                ->nullOnDelete();

            // Paket tour
            $table->foreignId('tour_package_id')
                ->nullable()
                ->constrained('tour_packages')
                ->nullOnDelete();

            // Kendaraan
            $table->foreignId('vehicle_id')
                ->nullable()
                ->constrained('vehicles')
                ->nullOnDelete();

            // Informasi perjalanan
            $table->foreignId('destination_id')
                ->nullable()
                ->constrained('destinations')
                ->nullOnDelete();

            $table->date('booking_date');

            $table->unsignedInteger('number_of_people')->default(1);

            // Harga
            $table->decimal('base_price', 15, 2)->default(0);
            $table->decimal('vehicle_price', 15, 2)->default(0);
            $table->decimal('total_price', 15, 2)->default(0);

            // Catatan customer
            $table->text('notes')->nullable();

            // Status booking
            $table->enum('status', [
                'pending',
                'confirmed',
                'in_progress',
                'completed',
                'cancelled',
            ])->default('pending');

            // Status pembayaran
            $table->enum('payment_status', [
                'unpaid',
                'partial',
                'paid',
                'refunded',
            ])->default('unpaid');

            $table->timestamps();

            $table->index('booking_date');
            $table->index('status');
            $table->index('payment_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};