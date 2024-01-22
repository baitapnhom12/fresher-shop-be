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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cart_id');
            $table->dropConstrainedForeignId('product_id');
            $table->dropColumn(['size_id', 'address_id', 'payment_method_id', 'price', 'quantity']);
            $table->string('sku', 20)->after('id')->unique('sku');
            $table->tinyInteger('payment_method')->after('status')->default(0);
            $table->dateTime('order_date')->after('payment_method');
            $table->string('receiver', 50);
            $table->string('phone', 10)->after('receiver');
            $table->string('shipping_address', 100)->after('phone');
            $table->string('provider', 100)->after('shipping_address');
            $table->string('account_number', 20)->after('provider');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->string('account_number', 20)->after('amount');
        });

        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders', 'id');
            $table->foreignId('product_id')->constrained('products', 'id');
            $table->integer('quantity');
            $table->double('price');
            $table->string('size', 50);
            $table->double('discount')->default(0);
            $table->timestamps();
        });

        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('sku', 20)->unique('sku');
            $table->tinyInteger('type');
            $table->integer('usage_count');
            $table->double('discount');
            $table->dateTime('expired_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('order_products');
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('account_number');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('cart_id')->after('id')->constrained('carts', 'id');
            $table->foreignId('product_id')->after('cart_id')->constrained('products', 'id');
            $table->unsignedBigInteger('size_id')->after('product_id')->nullable();
            $table->unsignedBigInteger('address_id')->after('cart_id');
            $table->unsignedBigInteger('payment_method_id')->after('address_id');
            $table->double('price')->after('payment_method_id');
            $table->integer('quantity')->after('discount');
            $table->dropColumn(['receiver', 'payment_method', 'order_date', 'phone', 'shipping_address', 'provider', 'account_number', 'sku']);
        });
    }
};
