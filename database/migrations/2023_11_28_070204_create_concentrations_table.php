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
        Schema::create('concentrations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('slug', 50)->unique('slug')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->string('slug', 50)->after('name')->unique('slug')->nullable();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('concentration_id')->after('brand_id')->nullable()->constrained('concentrations', 'id');
            $table->dropColumn('price');
            $table->string('slug', 50)->nullable()->change();
        });

        Schema::table('quantities', function (Blueprint $table) {
            $table->dropConstrainedForeignId('color_id');
            $table->unique(['product_id', 'size_id']);
            $table->dropColumn('add_price');
            $table->double('price');
        });

        Schema::drop('colors');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('quantities', function (Blueprint $table) {
            $table->foreignId('color_id')->constrained('colors', 'id');
            $table->unique(['product_id', 'size_id', 'color_id']);
            $table->dropUnique(['product_id', 'size_id']);
            $table->double('add_price')->default(0);
            $table->dropColumn('price');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('concentration_id');
            $table->double('price');
            $table->string('slug', 255)->nullable()->change();
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::dropIfExists('concentrations');
    }
};
