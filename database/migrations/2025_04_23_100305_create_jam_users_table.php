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
        Schema::create('jam_users', function (Blueprint $table) {
            $table->foreignId('jam_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['creator', 'participant', 'guide'])->default('participant');
            $table->timestamp('joined_at')->useCurrent();
            $table->primary(['jam_id', 'user_id']);
            $table->boolean('can_edit_jamboard')->default(false);
            $table->boolean('can_add_travellers')->default(false);
            $table->boolean('can_edit_budget')->default(false);
            $table->boolean('can_add_destinations')->default(false);
            $table->boolean('can_add_travelers')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jam_users');
    }
};
