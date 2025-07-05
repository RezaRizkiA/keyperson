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
            $table->bigInteger('refId')->nullable();
            $table->string('status')->default('pending')->nullable();

            // --- Data yang kita KIRIM ke iPaymu (dari payload) ---
            $table->string('via')->nullable();
            $table->string('channel')->nullable();
            $table->bigInteger('amount')->nullable();
            $table->text('trx_desc')->nullable(); // isi misal: appointment nama user dan expert
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('feeDirection')->default('MERCHANT')->nullable(); // isi dari ipaymu

            $table->string('trx_id')->nullable();                            // isi data dari ipaymu
            $table->string('sessionID')->nullable();                         // isi data dari ipaymu
            $table->text('paymentNo')->nullable();
            $table->string('paymentName')->nullable();
            $table->bigInteger('trx_fee')->nullable()->default('0');
            $table->bigInteger('total')->nullable();
            $table->string('url')->nullable();                               // isi data dari route payment
            $table->string('sid')->nullable();                               // isi data dari ipaymu
            $table->timestamp('payment_date')->nullable();
            $table->timestamp('expired_date')->nullable();
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
