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
        Schema::table('order_products', function (Blueprint $table) {
            $table->dropColumn(['discount']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['provider', 'account_number']);
            $table->unsignedBigInteger('payment_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_products', function (Blueprint $table) {
            $table->double('discount')->after('size');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('provider', 100)->after('shipping_address');
            $table->string('account_number', 20)->after('provider');
        });
    }
};
