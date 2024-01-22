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
        Schema::table('colors', function (Blueprint $table) {
            $table->dropColumn('add_price');
        });
        Schema::table('quantities', function (Blueprint $table) {
            $table->double('add_price')->after('quantity')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('colors', function (Blueprint $table) {
            Schema::table('colors', function (Blueprint $table) {
                $table->double('add_price')->after('product_id')->default(0);
            });
            Schema::table('quantities', function (Blueprint $table) {
                $table->dropColumn('add_price');
            });
        });
    }
};
