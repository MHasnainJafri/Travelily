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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained()->onDelete('cascade'); // The listing being booked
            $table->foreignId('host_id')->constrained('users')->onDelete('cascade'); // The host of the listing
            $table->foreignId('guest_id')->constrained('users')->onDelete('cascade'); // The guest making the booking
            $table->date('start_date'); // Booking start date (e.g., 12 Feb 2023)
            $table->date('end_date'); // Booking end date (e.g., 18 Feb 2023)
            $table->integer('num_people'); // Number of people (e.g., 4)
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Booking status
            $table->decimal('total_price', 8, 2)->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
