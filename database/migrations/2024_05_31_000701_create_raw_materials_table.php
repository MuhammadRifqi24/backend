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
        Schema::create('raw_materials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cafe_id');
            $table->unsignedBigInteger('stan_id')->nullable();
            $table->unsignedBigInteger('raw_material_category_id');
            $table->string('name');
            $table->string('image')->nullable();
            $table->longText('description')->nullable();
            $table->string('harga_beli')->default(0)->nullable();
            $table->string('harga_jual')->default(0)->nullable();
            $table->boolean('is_stock')->default(false);
            $table->boolean('status')->default(false);
            $table->uuid('uuid');
            $table->timestamps();
            $table->foreign('cafe_id')->references('id')->on('cafes');
            $table->foreign('stan_id')->references('id')->on('stans');
            $table->foreign('raw_material_category_id')->references('id')->on('raw_material_categories')->onDelete('restrict');;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_materials');
    }
};
