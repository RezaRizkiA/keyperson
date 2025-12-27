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
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('slug')->unique();
            $table->string('title')->nullable();
            $table->text('about')->nullable();
            $table->integer('price')->nullable();
            $table->json('type')->nullable();
            $table->text('experiences')->nullable();
            $table->text('licenses')->nullable();
            $table->text('gallerys')->nullable();
            $table->text('socials')->nullable();
            $table->string('background')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('experts');
    }
};
