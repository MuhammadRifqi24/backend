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
        Schema::create('call_waiter', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('table_info_id')->nullable();
            $table->unsignedBigInteger('cafe_id');
            $table->string('note')->nullable();
            $table->boolean('status')->default(false);
            $table->uuid('uuid');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('table_info_id')->references('id')->on('table_info')->onDelete('set null');
            $table->foreign('cafe_id')->references('id')->on('cafes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_waiter');
    }
};
