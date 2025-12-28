<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabel untuk menyimpan saldo deposit/quota perusahaan (B2B)
     * Setiap client (perusahaan) memiliki satu saldo quota
     */
    public function up(): void
    {
        Schema::create('client_quotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->unique()->constrained('clients')->onDelete('cascade');
            $table->bigInteger('balance')->default(0); // Saldo dalam Rupiah
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_quotas');
    }
};
