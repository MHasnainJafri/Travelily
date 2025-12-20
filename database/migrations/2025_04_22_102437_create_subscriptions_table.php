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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('plan_id');
            $table->unsignedBigInteger('card_id')->nullable();
            $table->string('stripe_subscription_id')->collation('utf8mb4_unicode_ci');
            $table->string('stripe_status')->collation('utf8mb4_unicode_ci');
            $table->string('stripe_price')->nullable()->collation('utf8mb4_unicode_ci');
            $table->unsignedInteger('quantity')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('plan_id')->references('id')->on('plans')->cascadeOnDelete();
            $table->foreign('card_id')->references('id')->on('cards')->cascadeOnDelete();

            $table->primary('id', 'subscriptions_primary');
            $table->index(['user_id', 'stripe_status'], 'subscriptions_user_id_stripe_status_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
