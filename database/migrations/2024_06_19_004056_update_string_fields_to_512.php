<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStringFieldsTo512 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('name', 512)->change();
            $table->string('phone_number', 512)->change();
            $table->string('city', 512)->change();
            $table->string('postal_code', 512)->change();
        });

        Schema::table('attributes', function (Blueprint $table) {
            $table->string('name', 512)->change();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('name', 512)->change();
        });

        Schema::table('goods', function (Blueprint $table) {
            $table->string('sku', 512)->change();
            $table->string('name', 512)->change();
            $table->string('image', 512)->change();
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->string('name', 512)->change();
        });

        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->string('email', 512)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Implement the reverse logic to revert fields back to previous state if necessary
    }
}

