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
        // =====================
        // CACHE TABLE
        // =====================
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();

            $table->mediumText('value');

            $table->integer('expiration')->index(); // 🔥 indexed for performance
        });

        // =====================
        // CACHE LOCKS TABLE
        // =====================
        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();

            $table->string('owner');

            $table->integer('expiration')->index(); // 🔥 indexed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 🔥 correct order
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
    }
};