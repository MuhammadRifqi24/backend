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
        Schema::create('raw_material_stock_outs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('raw_material_stock_id');
            $table->char('qty');
            $table->text('description')->nullable();
            $table->dateTime('date');
            $table->timestamps();
            $table->foreign('raw_material_stock_id')->references('id')->on('raw_material_stocks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_material_stock_outs');
    }
};
