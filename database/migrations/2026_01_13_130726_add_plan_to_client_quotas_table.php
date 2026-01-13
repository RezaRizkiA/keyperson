<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Menambahkan kolom plan untuk menentukan paket langganan client:
     * - starter: 5 juta
     * - professional: 25 juta
     * - enterprise: 100 juta
     * - custom: sesuai plan_limit
     */
    public function up(): void
    {
        Schema::table('client_quotas', function (Blueprint $table) {
            $table->enum('plan', ['starter', 'professional', 'enterprise', 'custom'])
                ->default('starter')
                ->after('balance');
            $table->bigInteger('plan_limit')
                ->default(5000000) // Default 5 juta (starter)
                ->after('plan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_quotas', function (Blueprint $table) {
            $table->dropColumn(['plan', 'plan_limit']);
        });
    }
};
