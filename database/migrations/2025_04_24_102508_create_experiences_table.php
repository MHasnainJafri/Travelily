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
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // The host creating the experience
            $table->string('title'); // Experience Title
            $table->text('description'); // Description
            $table->string('location'); // Your Location
            // / need to add coordinates
            $table->date('start_date'); // Start date (e.g., Aug 17)
            $table->date('end_date'); // End date (e.g., Aug 23)
            $table->decimal('min_price', 8, 2); // Minimum price (e.g., $0)
            $table->decimal('max_price', 8, 2); // Maximum price (e.g., $15)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};
