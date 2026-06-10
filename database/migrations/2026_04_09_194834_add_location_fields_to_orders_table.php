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
        Schema::table('orders', function (Blueprint $table) {
            // ✅ DTDC API ke liye ye 3 columns zaroori hain
            $table->string('pincode')->nullable()->after('address');
            $table->string('city')->nullable()->after('pincode');
            $table->string('state')->nullable()->after('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Rollback karte waqt ye columns remove ho jayenge
            $table->dropColumn(['pincode', 'city', 'state']);
        });
    }
};