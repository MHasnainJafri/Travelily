<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add time to itineraries
        if (Schema::hasTable('itineraries')) {
            Schema::table('itineraries', function (Blueprint $table) {
                if (!Schema::hasColumn('itineraries', 'time')) {
                    $table->time('time')->nullable()->after('date');
                }
                if (!Schema::hasColumn('itineraries', 'start_time')) {
                    $table->time('start_time')->nullable()->after('time');
                }
                if (!Schema::hasColumn('itineraries', 'end_time')) {
                    $table->time('end_time')->nullable()->after('start_time');
                }
                if (!Schema::hasColumn('itineraries', 'amenities')) {
                    $table->json('amenities')->nullable();
                }
                if (!Schema::hasColumn('itineraries', 'activity_category')) {
                    $table->string('activity_category')->nullable();
                }
            });
        }

        // Add permissions to jam_invitations
        if (Schema::hasTable('jam_invitations')) {
            Schema::table('jam_invitations', function (Blueprint $table) {
                if (!Schema::hasColumn('jam_invitations', 'can_edit_jamboard')) {
                    $table->boolean('can_edit_jamboard')->default(false);
                }
                if (!Schema::hasColumn('jam_invitations', 'can_add_travelers')) {
                    $table->boolean('can_add_travelers')->default(false);
                }
                if (!Schema::hasColumn('jam_invitations', 'can_edit_budget')) {
                    $table->boolean('can_edit_budget')->default(false);
                }
                if (!Schema::hasColumn('jam_invitations', 'can_add_destinations')) {
                    $table->boolean('can_add_destinations')->default(false);
                }
            });
        }

        // Add fields to jams table
        if (Schema::hasTable('jams')) {
            Schema::table('jams', function (Blueprint $table) {
                if (!Schema::hasColumn('jams', 'total_travelers_limit')) {
                    $table->integer('total_travelers_limit')->nullable();
                }
                if (!Schema::hasColumn('jams', 'start_time')) {
                    $table->time('start_time')->nullable();
                }
                if (!Schema::hasColumn('jams', 'stay_time_days')) {
                    $table->integer('stay_time_days')->nullable();
                }
            });
        }

        // Add start_date to tasks
        if (Schema::hasTable('tasks')) {
            Schema::table('tasks', function (Blueprint $table) {
                if (!Schema::hasColumn('tasks', 'start_date')) {
                    $table->date('start_date')->nullable()->after('due_date');
                }
            });
        }

        // Add status to jam_users
        if (Schema::hasTable('jam_users')) {
            Schema::table('jam_users', function (Blueprint $table) {
                if (!Schema::hasColumn('jam_users', 'status')) {
                    $table->enum('status', ['active', 'left', 'removed'])->default('active');
                }
            });
        }

        // Add fields to listings
        if (Schema::hasTable('listings')) {
            Schema::table('listings', function (Blueprint $table) {
                if (!Schema::hasColumn('listings', 'offerings')) {
                    $table->json('offerings')->nullable();
                }
                if (!Schema::hasColumn('listings', 'dates_available')) {
                    $table->json('dates_available')->nullable();
                }
                if (!Schema::hasColumn('listings', 'approval_status')) {
                    $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
                }
            });
        }

        // Add fields to bookings
        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                if (!Schema::hasColumn('bookings', 'experience_id')) {
                    $table->foreignId('experience_id')->nullable()->constrained('experiences')->onDelete('cascade');
                }
                if (!Schema::hasColumn('bookings', 'guide_id')) {
                    $table->foreignId('guide_id')->nullable()->constrained('users')->onDelete('cascade');
                }
            });
        }

        // Add fields to user_profiles
        if (Schema::hasTable('user_profiles')) {
            Schema::table('user_profiles', function (Blueprint $table) {
                if (!Schema::hasColumn('user_profiles', 'guide_price')) {
                    $table->decimal('guide_price', 8, 2)->nullable();
                }
                if (!Schema::hasColumn('user_profiles', 'hosting_count')) {
                    $table->integer('hosting_count')->default(0);
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('itineraries')) {
            Schema::table('itineraries', function (Blueprint $table) {
                $table->dropColumn(['time', 'start_time', 'end_time', 'amenities', 'activity_category']);
            });
        }

        if (Schema::hasTable('jam_invitations')) {
            Schema::table('jam_invitations', function (Blueprint $table) {
                $table->dropColumn(['can_edit_jamboard', 'can_add_travelers', 'can_edit_budget', 'can_add_destinations']);
            });
        }

        if (Schema::hasTable('jams')) {
            Schema::table('jams', function (Blueprint $table) {
                $table->dropColumn(['total_travelers_limit', 'start_time', 'stay_time_days']);
            });
        }

        if (Schema::hasTable('tasks')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->dropColumn('start_date');
            });
        }

        if (Schema::hasTable('jam_users')) {
            Schema::table('jam_users', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }

        if (Schema::hasTable('listings')) {
            Schema::table('listings', function (Blueprint $table) {
                $table->dropColumn(['offerings', 'dates_available', 'approval_status']);
            });
        }

        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropColumn(['experience_id', 'guide_id']);
            });
        }

        if (Schema::hasTable('user_profiles')) {
            Schema::table('user_profiles', function (Blueprint $table) {
                $table->dropColumn(['guide_price', 'hosting_count']);
            });
        }
    }
};
