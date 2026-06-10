<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Jaise: DIWALI20
            $table->integer('discount_percent'); // Jaise: 20
            
            // 🔥 YEH DO NAYE COLUMNS ADD KIYE HAIN LIMIT KE LIYE
            $table->integer('usage_limit')->nullable(); 
            $table->integer('times_used')->default(0);  
            
            $table->boolean('is_active')->default(true); // Chalu hai ya band
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};