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
        Schema::table('products', function (Blueprint $table) {
            $table->double('price')->after('name');
        });

        Schema::table('colors', function (Blueprint $table) {
            $table->double('add_price')->after('product_id')->default(0);
        });

        Schema::drop('prices');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('price');
        });

        Schema::table('colors', function (Blueprint $table) {
            $table->dropColumn('add_price');
        });

        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->double('price');
            $table->foreignId('color_id')->constrained('colors', 'id');
            $table->timestamps();
        });
    }
};
