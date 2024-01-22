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
        Schema::create('discount_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products', 'id')->after('id');
            $table->foreignId('discount_id')->constrained('discounts', 'id')->after('product_id');
            $table->dateTime('promotion_term')->nullable();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('discount_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_products');

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('discount_id')->constrained('discounts', 'id')->after('brand_id');
        });
    }
};
