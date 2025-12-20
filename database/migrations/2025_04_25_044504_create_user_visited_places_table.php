<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserVisitedPlacesTable extends Migration
{
    public function up()
    {
        Schema::create('user_visited_places', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('place_name'); // Name of the place (e.g., "Lake Louise")
            $table->string('address')->nullable(); // Full address from Google Places
            $table->string('google_place_id')->nullable(); // Full address from Google Places
            $table->geometry('coordinates', subtype: 'point')->nullable();
            $table->integer('rank')->default(1); // Rank (1 to 5 for top 5 places)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_visited_places');
    }
}
