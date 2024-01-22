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
            $table->id();
            $table->foreignId('category_id')->constrained('categories', 'id');
            $table->foreignId('brand_id')->constrained('brands', 'id');
            $table->foreignId('discount_id')->constrained('discounts', 'id');
            $table->string('name');
            $table->integer('quantity');
            $table->double('price');
            $table->json('image')->nullable();
            $table->longText('description');
            $table->timestamps();
            $table->softDeletes();
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
