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
        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug', 50)->nullable()->change();
        });

        Schema::table('sizes', function (Blueprint $table) {
            $table->string('name', 50)->change();
        });

        Schema::table('colors', function (Blueprint $table) {
            $table->string('name', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug', 255)->nullable()->change();
        });

        Schema::table('sizes', function (Blueprint $table) {
            $table->string('name', 255)->change();
        });

        Schema::table('colors', function (Blueprint $table) {
            $table->string('name', 255)->change();
        });
    }
};
