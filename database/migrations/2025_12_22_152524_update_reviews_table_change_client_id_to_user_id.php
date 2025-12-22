<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Change reviews.client_id to reviews.user_id
     * Because the person who writes review is the USER who booked,
     * not the CLIENT (which is just company metadata)
     */
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Drop old client_id foreign key and column
            $table->dropForeign(['client_id']);
            $table->dropColumn('client_id');
            
            // Add user_id foreign key (who writes the review)
            $table->foreignId('user_id')
                ->after('appointment_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->comment('User who wrote the review (same as appointment.user_id)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Drop new user_id
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            
            // Restore old client_id
            $table->foreignId('client_id')
                ->after('appointment_id')
                ->constrained('clients')
                ->onDelete('cascade');
        });
    }
};
