<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->integer('usage_limit')->nullable()->after('discount_percent'); // Kitni baar allow karna hai
            $table->integer('times_used')->default(0)->after('usage_limit');        // Kitni baar use ho gaya
        });
    }

    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn(['usage_limit', 'times_used']);
        });
    }
};
