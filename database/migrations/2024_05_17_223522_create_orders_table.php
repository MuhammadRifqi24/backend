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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('table_info_id')->nullable();
            $table->unsignedBigInteger('cafe_id');
            $table->string('customer_name');
            $table->string('note')->nullable();
            $table->string('total_price');
            $table->boolean('status')->default(false);
            $table->boolean('payment_status')->default(false);
            $table->boolean('order_type');
            $table->uuid('uuid');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('table_info_id')->references('id')->on('table_info')->onDelete('set null');
            $table->foreign('cafe_id')->references('id')->on('cafes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
