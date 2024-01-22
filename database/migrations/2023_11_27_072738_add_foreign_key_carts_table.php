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
        Schema::table('carts', function (Blueprint $table) {
            $table->unsignedBigInteger('size_id')->after('product_id');
            $table->unsignedBigInteger('color_id')->after('size_id');
            $table->dropTimestamps();
            $table->dropSoftDeletes();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0)->after('id');
            $table->foreignId('product_id')->constrained('products', 'id')->after('user_id');
            $table->unsignedBigInteger('size_id')->after('product_id')->nullable();
            $table->unsignedBigInteger('color_id')->after('size_id')->nullable();
            $table->foreignId('cart_id')->constrained('carts', 'id')->after('color_id')->nullable();
            $table->unsignedBigInteger('address_id')->after('cart_id');
            $table->unsignedBigInteger('payment_method_id')->after('address_id');
            $table->double('price')->after('payment_method_id');
            $table->double('shipping_fee')->after('price');
            $table->double('discount')->after('shipping_fee');
            $table->integer('quantity')->after('discount');
        });

        Schema::drop('order_products');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('product_id');
            $table->dropConstrainedForeignId('cart_id');
            $table->dropColumn(['status', 'size_id', 'color_id', 'address_id', 'payment_method_id', 'price', 'shipping_fee', 'discount', 'quantity']);
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->timestamps();
            $table->softDeletes();
            $table->dropColumn(['size_id', 'color_id']);
        });

        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders', 'id');
            $table->foreignId('product_id')->constrained('products', 'id');
            $table->integer('quantity');
            $table->double('shipping_amount')->nullable();
            $table->timestamps();
        });
    }
};
