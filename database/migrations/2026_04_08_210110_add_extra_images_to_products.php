<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up() 
    {
        // Agar column nahi hai, sirf tabhi banao
        if (!Schema::hasColumn('products', 'gallery')) {
            Schema::table('products', function (Blueprint $table) {
                $table->json('gallery')->nullable()->after('image');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('products', 'gallery')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('gallery');
            });
        }
    }
};