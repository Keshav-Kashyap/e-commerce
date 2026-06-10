<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('coupons', function (Blueprint $table) {
        // max_discount column add kar rahe hain (nullable rakhenge agar koi limit na lagani ho)
        $table->decimal('max_discount', 8, 2)->nullable()->after('discount_percent'); // 'discount_percent' ke baad add hoga (aapke database structure ke hisaab se adjust kar lena)
    });
}

public function down()
{
    Schema::table('coupons', function (Blueprint $table) {
        $table->dropColumn('max_discount');
    });
}
};
