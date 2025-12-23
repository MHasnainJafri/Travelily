<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jam_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jam_id')->constrained('jams')->onDelete('cascade');
            $table->string('title');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('category', ['food', 'transport', 'stay', 'activity', 'other'])->default('other');
            $table->date('date');
            $table->foreignId('paid_by_user_id')->constrained('users')->onDelete('cascade');
            $table->json('split_with')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jam_expenses');
    }
};
