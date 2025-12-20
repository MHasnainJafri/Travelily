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
        Schema::create('user_verifications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // ID Document Data (CNIC / Passport)
            $table->enum('id_type', ['cnic', 'passport'])->nullable(); // User-selected or auto-detected
            $table->string('id_number')->nullable();
            $table->string('id_front_image')->nullable(); // Local path or S3 URL
            $table->string('id_back_image')->nullable()->nullable(); // For CNIC
            $table->boolean('id_verified')->default(false);

            // Face Scan Data
            $table->string('face_image')->nullable(); // Path to face scan photo
            $table->boolean('face_verified')->default(false);

            // Optional verification metadata
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('verified');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_verifications');
    }
};
