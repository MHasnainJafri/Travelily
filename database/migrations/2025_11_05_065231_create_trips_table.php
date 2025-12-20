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
       // database/migrations/xxxx_xx_xx_create_trips_table.php
Schema::create('trips', function (Blueprint $table) {
    $table->id();
    $table->string('jamboard_name');
    $table->string('destination');
    $table->json('destination_details')->nullable();

    $table->date('start_date')->nullable();
    $table->date('end_date')->nullable();
    $table->time('time')->nullable();

    $table->enum('looking_for', ['tripmate', 'guide', 'host'])->default('tripmate');
    $table->string('image')->nullable();

    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('jam_id')->nullable()->constrained()->onDelete('set null');

    $table->timestamps();
    $table->softDeletes();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
