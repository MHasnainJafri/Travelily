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
        Schema::create('user_recommended_places', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('place_name'); // Name of the place (e.g., "Lake Louise")
            $table->string('address')->nullable(); // Full address from Google Places
            $table->string('google_place_id')->nullable(); // Full address from Google Places
            $table->geometry('coordinates', subtype: 'point')->nullable();
            $table->integer('rank')->default(1); // Rank (1 to 5 for top 5 places)
            $table->timestamps();

            // Ensure a user can only have one place per rank
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_recommended_places');
    }
};
