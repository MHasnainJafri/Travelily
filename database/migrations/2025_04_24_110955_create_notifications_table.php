<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipient_id')->constrained('users')->onDelete('cascade'); // User receiving the notification
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade'); // User who triggered the notification
            $table->string('type'); // Type of notification (e.g., 'liked_post', 'commented_photo', 'followed')
            $table->string('notifiable_type')->nullable(); // Polymorphic type (e.g., 'App\Models\Post', 'App\Models\Story')
            $table->unsignedBigInteger('notifiable_id')->nullable(); // Polymorphic ID (e.g., post ID, story ID)
            $table->text('message'); // Notification message (e.g., "Inverness McKenzie liked your post")
            $table->boolean('read')->default(false); // Whether the notification has been read
            $table->timestamps();

            // Index for polymorphic relationship
            $table->index(['notifiable_type', 'notifiable_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
