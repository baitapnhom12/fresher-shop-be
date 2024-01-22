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
            $table->dropConstrainedForeignId('product_id');
        });

        Schema::table('sizes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('colors', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained('products', 'id')->after('id');
        });

        Schema::table('sizes', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained('products', 'id')->after('id');
        });
    }
};
