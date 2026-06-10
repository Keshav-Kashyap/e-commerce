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
    Schema::table('orders', function (Blueprint $table) {
        // Subtotal (Original price bina discount ke)
        $table->decimal('subtotal', 10, 2)->nullable()->after('address');
        
        // Coupon Code (Jo customer ne use kiya)
        $table->string('coupon_code')->nullable()->after('subtotal');
        
        // Discount Amount (Kitne rupaye kam huye)
        $table->decimal('discount_amount', 10, 2)->default(0)->after('coupon_code');
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn(['subtotal', 'coupon_code', 'discount_amount']);
    });
}
};
