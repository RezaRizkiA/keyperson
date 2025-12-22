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
        Schema::table('experts', function (Blueprint $table) {
            $table->decimal('rating', 3, 2)->default(0)->after('price')->comment('Average rating (0.00 - 5.00)');
            $table->unsignedInteger('total_reviews')->default(0)->after('rating')->comment('Total number of reviews');
            $table->unsignedInteger('total_sessions')->default(0)->after('total_reviews')->comment('Total completed appointments');
            
            // Index for filtering/sorting
            $table->index(['rating', 'total_reviews']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('experts', function (Blueprint $table) {
            $table->dropIndex(['rating', 'total_reviews']);
            $table->dropColumn(['rating', 'total_reviews', 'total_sessions']);
        });
    }
};
