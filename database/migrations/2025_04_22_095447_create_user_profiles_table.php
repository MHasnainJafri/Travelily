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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->text('bio')->nullable();
            $table->float('rating', 3, 1)->default(0.0); // For 5.0 rating
            $table->integer('followers_count')->default(0); // For 2,765 followers
            $table->integer('petals_count')->default(0); // For 100 petals (possibly a custom metric)
            $table->integer('trips_count')->default(0); // For 1,500 trips
            $table->string('local_expert_place_name')->nullable();
            $table->string('local_expert_google_place_id')->nullable();
            $table->string('short_video')->nullable();
            $table->string('facebook')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('linkedin')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
