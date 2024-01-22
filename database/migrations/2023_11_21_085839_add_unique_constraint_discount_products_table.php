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
        Schema::table('discount_products', function (Blueprint $table) {
            $table->unique(['product_id', 'discount_id']);
        });

        Schema::table('quantities', function (Blueprint $table) {
            $table->unique(['product_id', 'color_id', 'size_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('discount_products', function (Blueprint $table) {
            $table->dropUnique(['product_id', 'discount_id']);
        });

        Schema::table('quantities', function (Blueprint $table) {
            $table->dropUnique(['product_id', 'color_id', 'size_id']);
        });
    }
};
