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
        Schema::create('itineraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jam_id')->constrained()->onDelete('cascade');
            $table->string('type'); // Type of itinerary item (e.g., 'travel_guide', 'experience', 'route')
            $table->string('title'); // Title (e.g., "San Marino to Como")
            $table->text('description')->nullable(); // Description or details
            $table->json('details')->nullable(); // JSON field for additional details (e.g., route coordinates)
            $table->date('date')->nullable(); // Date of the itinerary item
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itineraries');
    }
};
