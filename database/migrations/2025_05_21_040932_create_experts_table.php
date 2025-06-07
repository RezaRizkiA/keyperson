<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('experts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->nullable()->constrained('users')->onDelete('cascade');
            $table->string('expertise')->nullable();
            $table->text('biography')->nullable();
            $table->text('experiences')->nullable();
            $table->text('licenses')->nullable();
            $table->text('gallerys')->nullable();
            $table->string('background')->nullable();
            $table->text('expertise_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('experts');
    }
};
