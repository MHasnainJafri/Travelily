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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jam_id')->constrained()->onDelete('cascade');
            $table->string('title'); // Task title (e.g., "Find new travel buddies")
            $table->text('description')->nullable(); // Task description
            $table->date('due_date')->nullable(); 
            $table->enum('status',['pending','complete'])->nullable(); // Task description
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
