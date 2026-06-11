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
            if (!Schema::hasColumn('coupons', 'usage_limit')) {
                $table->integer('usage_limit')->nullable()->after('discount_percent'); // Kitni baar allow karna hai
            }
            if (!Schema::hasColumn('coupons', 'times_used')) {
                $table->integer('times_used')->default(0)->after('usage_limit');        // Kitni baar use ho gaya
            }
        });
    }

    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {
            if (Schema::hasColumn('coupons', 'usage_limit')) {
                $table->dropColumn('usage_limit');
            }
            if (Schema::hasColumn('coupons', 'times_used')) {
                $table->dropColumn('times_used');
            }
        });
    }
};
