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
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropConstrainedForeignId('order_id');
        });

        Schema::table('payment_methods', function (Blueprint $table) {
            $table->unique(['provider', 'account_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('order_id')->nullable()->constrained('orders', 'id');
        });

        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropUnique(['provider', 'account_number']);
        });
    }
};
