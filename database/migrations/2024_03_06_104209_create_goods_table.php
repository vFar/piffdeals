<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->unsignedBigInteger('stock_quantity');
            $table->string('status')->default('Deaktivizēts');
            $table->unsignedBigInteger('group_id')->nullable();
            $table->unsignedBigInteger('attribute_id')->nullable();
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('set null');
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('set null');
            $table->timestamps();

            
        });
    }

    public function down()
    {
        Schema::dropIfExists('goods');
    }
};
