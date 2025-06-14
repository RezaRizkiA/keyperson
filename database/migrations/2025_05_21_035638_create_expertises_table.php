<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expertises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('expertises')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->integer('level')->nullable();
            $table->integer('order')->nullable();
            $table->string('ilustration_img')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expertises');
    }
};
