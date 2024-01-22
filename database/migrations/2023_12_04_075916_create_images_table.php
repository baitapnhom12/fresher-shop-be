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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users', 'id')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products', 'id')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories', 'id')->cascadeOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained('brands', 'id')->cascadeOnDelete();
            $table->foreignId('banner_id')->nullable()->constrained('banners', 'id')->cascadeOnDelete();
            $table->string('path');
            $table->tinyInteger('main')->default(0);
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');

        Schema::table('users', function (Blueprint $table) {
            $table->json('image');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->json('image');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->json('image');
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->json('image');
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->json('image');
        });
    }
};
