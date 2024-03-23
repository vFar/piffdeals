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
        Schema::create('category_good', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('good_id');
            $table->unsignedBigInteger('category_id');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('good_id')->references('id')->on('goods')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('goods_categories')->onDelete('cascade');

            // Ensure the combination of good_id and category_id is unique
            $table->unique(['good_id', 'category_id'], 'good_category_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_good');
    }
};
