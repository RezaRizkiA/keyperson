<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * B2B Migration: Add foreign key constraint for users.client_id
 * 
 * Kolom client_id sudah dibuat di create_users_table migration sebagai unsignedBigInteger.
 * FK constraint harus ditambahkan setelah clients table dibuat.
 * 
 * Relasi: User belongsTo Client (karyawan milik perusahaan)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('client_id')
                  ->references('id')
                  ->on('clients')
                  ->nullOnDelete(); // Jika client dihapus, set client_id = null
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
        });
    }
};
