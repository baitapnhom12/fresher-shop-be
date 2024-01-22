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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained('orders', 'id');
            $table->foreignId('product_id')->nullable()->constrained('products', 'id');
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->foreignId('post_id')->nullable()->constrained('posts', 'id');
            $table->tinyInteger('rating')->nullable();
            $table->text('comment');
            $table->unsignedBigInteger('reply_to')->nullable();
            $table->timestamps();
        });

        Schema::table('images', function (Blueprint $table) {
            $table->foreignId('review_id')->nullable()->after('banner_id')->constrained('reviews', 'id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('images', function (Blueprint $table) {
            $table->dropConstrainedForeignId('review_id');
        });

        Schema::dropIfExists('reviews');
    }
};
