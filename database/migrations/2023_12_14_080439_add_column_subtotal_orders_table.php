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
            $table->double('subtotal');
        });

        Schema::table('payment_methods', function (Blueprint $table) {
            $table->unique(['user_id', 'provider', 'account_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('subtotal');
        });

        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'provider', 'account_number']);
        });
    }
};
