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
        // Interests
        Schema::create('interests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Pivot: user <-> interests
        Schema::create('user_interests', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('interest_id')->constrained()->onDelete('cascade');
            $table->primary(['user_id', 'interest_id']);
            $table->timestamps();
        });

        // Pivot: user <-> interests for buddy
        Schema::create('user_buddy_interests', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('buddy_interest_id')->constrained('interests')->onDelete('cascade');
            $table->primary(['user_id', 'buddy_interest_id']);
            $table->timestamps();
        });

        // Travel Activities
        Schema::create('travel_activities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Travel With Options
        Schema::create('travel_with_options', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Pivot: user <-> travel activities
        Schema::create('user_travel_activities', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('travel_activity_id')->constrained()->onDelete('cascade');
            $table->primary(['user_id', 'travel_activity_id']);
            $table->timestamps();
        });

        // Pivot: user <-> travel with options
        Schema::create('user_travel_with_options', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('travel_with_option_id')->constrained()->onDelete('cascade');
            $table->primary(['user_id', 'travel_with_option_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_travel_with_options');
        Schema::dropIfExists('user_travel_activities');
        Schema::dropIfExists('travel_with_options');
        Schema::dropIfExists('travel_activities');
        Schema::dropIfExists('user_buddy_interests');
        Schema::dropIfExists('user_interests');
        Schema::dropIfExists('interests');
    }
};
