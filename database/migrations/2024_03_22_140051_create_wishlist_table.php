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
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            // Ensure user_id references the users table correctly
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            // Ensure good_id references the goods table correctly
            $table->foreignId('good_id')->constrained()->onDelete('cascade'); 
            $table->timestamps();

            // Ensures a user cannot add the same good to the wishlist more than once
            $table->unique(['user_id', 'good_id'], 'user_good_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlist');
    }
};
