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
        Schema::create('appoinments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('expert_id')->nullable()->constrained('experts')->onDelete('cascade');
            $table->text('appoinment')->nullable();
            $table->timestamp('date_time')->nullable();
            $table->text('calendar_token')->nullable();
            $table->integer('hours')->nullable();
            $table->bigInteger('price')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appoinments');
    }
};
