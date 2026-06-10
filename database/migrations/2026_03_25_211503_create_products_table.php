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
        Schema::create('products', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('cascade');

            // =====================
            // PRODUCT DETAILS
            // =====================
            $table->string('name');

            $table->text('description')->nullable();

            // 🔥 FIXED (decimal price)
            $table->decimal('price', 10, 2);

            $table->string('image')->nullable();

            // =====================
            // EXTRA FIELDS
            // =====================
            $table->integer('stock')->default(0);

            // 🔥 indexed for fast filtering
            $table->string('category')->nullable()->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};