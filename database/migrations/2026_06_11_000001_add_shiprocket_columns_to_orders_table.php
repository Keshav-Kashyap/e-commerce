<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shiprocket_order_id')->nullable()->after('tracking_id');
            $table->string('shiprocket_shipment_id')->nullable()->after('shiprocket_order_id');
            $table->string('shiprocket_awb')->nullable()->after('shiprocket_shipment_id');
            $table->string('shiprocket_status')->nullable()->after('shiprocket_awb');
            $table->text('shiprocket_response')->nullable()->after('shiprocket_status');
            $table->text('shiprocket_sync_error')->nullable()->after('shiprocket_response');
            $table->timestamp('shiprocket_synced_at')->nullable()->after('shiprocket_sync_error');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shiprocket_order_id',
                'shiprocket_shipment_id',
                'shiprocket_awb',
                'shiprocket_status',
                'shiprocket_response',
                'shiprocket_sync_error',
                'shiprocket_synced_at',
            ]);
        });
    }
};