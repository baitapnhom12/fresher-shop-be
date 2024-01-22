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
        Schema::create('quantities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products', 'id');
            $table->foreignId('color_id')->constrained('colors', 'id');
            $table->foreignId('size_id')->constrained('sizes', 'id');
            $table->integer('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quantities');
    }
};
