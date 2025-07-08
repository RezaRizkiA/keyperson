<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->nullable()->constrained('appointments')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // --- Data dari sisi kita & status ---
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('trx_desc')->nullable();
            $table->bigInteger('amount')->nullable();
            $table->bigInteger('referenceId')->nullable();


            // --- Data yang kita KIRIM ke iPaymu (dari payload) ---
            $table->string('sessionID')->nullable();
            $table->bigInteger('transactionId')->nullable()->unique();
            $table->string('via')->nullable();
            $table->string('channel')->nullable();
            $table->text('paymentNo')->nullable();
            $table->string('paymentName')->nullable();
            $table->bigInteger('total')->nullable();
            $table->bigInteger('trx_fee')->nullable()->default('0');
            $table->timestamp('expired_date')->nullable();
            $table->string('url')->nullable(); // isi data dari route payment

            $table->string('feeDirection')->default('MERCHANT')->nullable();

            $table->string('trx_id')->nullable()->unique(); // untuk menyimpan kode unik transaksi
            $table->string('status')->default('pending')->nullable();
            $table->string('sid')->nullable()->unique(); // isi data dari ipaymu
            $table->string('reference_id')->nullable(); // isi data dari ipaymu
            $table->timestamp('payment_date')->nullable();

            $table->bigInteger('tax_system')->nullable()->default('0');
            $table->boolean('trx_chacking')->default('0')->nullable();
            $table->boolean('trx_calender_process')->default('1')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
