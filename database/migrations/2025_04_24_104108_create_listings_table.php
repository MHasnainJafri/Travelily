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
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // The host creating the listing
            $table->string('title'); // e.g., "Luxury Seaside"
            $table->string('location'); // e.g., "Bologna, Italy"
            $table->text('description'); // Listing description
            $table->integer('max_people'); // Max number of people (e.g., 6)
            $table->integer('min_stay_days'); // Min stay (e.g., 2 days)
            $table->integer('num_rooms'); // Number of rooms (e.g., 3)
            $table->enum('status',[0,1])->default(1); // Number of rooms (e.g., 3)
            $table->enum('featured',[0,1])->default(0); // Number of rooms (e.g., 3)
            $table->decimal('price', 8, 2); // Price per night (e.g., $200)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
