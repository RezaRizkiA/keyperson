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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys
            $table->foreignId('appointment_id')->unique()->constrained('appointments')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('expert_id')->constrained('experts')->onDelete('cascade');
            $table->foreignId('skill_id')->nullable()->constrained('skills')->onDelete('set null');
            
            // Review data
            $table->unsignedTinyInteger('rating')->comment('Rating 1-5 stars');
            $table->text('review_text')->nullable()->comment('Written review/testimonial');
            $table->unsignedInteger('helpful_count')->default(0)->comment('How many found this helpful');
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['expert_id', 'rating']);
            $table->index(['skill_id', 'rating']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
