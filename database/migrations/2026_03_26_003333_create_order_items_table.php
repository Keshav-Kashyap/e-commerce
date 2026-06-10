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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            // =====================
            // FOREIGN KEYS
            // =====================
            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            // =====================
            // PRODUCT DETAILS
            // =====================
            $table->integer('quantity')->default(1);

            $table->decimal('price', 10, 2);

            $table->timestamps();

            // =====================
            // 🔥 PERFORMANCE INDEX
            // =====================
            $table->index('order_id');

            // =====================
            // 🔥 PREVENT DUPLICATE PRODUCT IN SAME ORDER
            // =====================
            $table->unique(['order_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};