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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('stripe_payment_method_id')->unique(); // e.g., "pm_1Nxxxx"
            $table->string('brand')->nullable(); // Visa, MasterCard, etc.
            $table->string('last4')->nullable(); // Last 4 digits
            $table->integer('exp_month')->nullable();
            $table->integer('exp_year')->nullable();

            $table->boolean('is_default')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
