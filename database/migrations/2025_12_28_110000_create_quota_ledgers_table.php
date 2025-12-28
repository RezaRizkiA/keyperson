<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * B2B Phase 2: Audit Trail - Quota Ledger
 * 
 * Mencatat history setiap transaksi quota (debit/credit)
 * untuk keperluan audit dan rekonsiliasi.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quota_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // User yang melakukan transaksi
            $table->foreignId('appointment_id')->nullable()->constrained('appointments')->nullOnDelete();
            $table->foreignId('transaction_id')->nullable()->constrained('transactions')->nullOnDelete();
            
            $table->enum('type', ['debit', 'credit']); // debit = usage/potong, credit = topup/refund
            $table->bigInteger('amount'); // Jumlah yang ditransaksikan
            $table->bigInteger('balance_before'); // Saldo sebelum transaksi
            $table->bigInteger('balance_after'); // Saldo setelah transaksi
            
            $table->string('reference_type'); // 'booking', 'topup', 'refund', 'adjustment'
            $table->string('description'); // Deskripsi transaksi
            
            $table->timestamps();
            
            // Index untuk query performa
            $table->index(['client_id', 'created_at']);
            $table->index('reference_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quota_ledgers');
    }
};
