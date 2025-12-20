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
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // The user creating the ad
            $table->string('title'); // Ad Title
            $table->integer('duration_days'); // Ad Duration (in days)
            $table->json('locations'); // Target locations (e.g., ["Paris", "London", "New York"])
            $table->json('age_ranges')->nullable(); // Audience age ranges (e.g., ["18-29", "30-45"])
            $table->json('genders')->nullable(); // Audience genders (e.g., ["Males", "Females"])
            $table->json('relationships')->nullable(); // Audience relationships (e.g., ["Singles", "Couples"])
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
