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
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('role')->default(0)->after('password')->comment('0: customer, 1:admin');
            $table->date('birthday')->after('role')->nullable();
            $table->string('phone')->after('birthday')->nullable();
            $table->json('image')->after('phone')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'birthday', 'phone', 'image', 'deleted_at']);
        });
    }
};
