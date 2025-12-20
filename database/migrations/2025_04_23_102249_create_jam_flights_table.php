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
        Schema::create('jam_flights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jam_id')->constrained()->onDelete('cascade');
            $table->enum('mode_of_transportation', ['airplane', 'bus', 'train', 'car', 'boat', 'motorcycle', 'other']);
            $table->string('from');
            // from coordinates

            $table->string('to');
            $table->date('date');
            $table->time('departure_time')->nullable();
            $table->time('arrival_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jam_flights');
    }
};
