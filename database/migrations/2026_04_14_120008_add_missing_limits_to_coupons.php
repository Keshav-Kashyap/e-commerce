<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            // Check karega ki agar column nahi hai, tabhi add karega
            if (!Schema::hasColumn('coupons', 'usage_limit')) {
                $table->integer('usage_limit')->nullable()->after('discount_percent');
            }
            if (!Schema::hasColumn('coupons', 'times_used')) {
                $table->integer('times_used')->default(0)->after('usage_limit');
            }
        });
    }

    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn(['usage_limit', 'times_used']);
        });
    }
};