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
        Schema::create('cafe_management', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cafe_id');
            $table->unsignedBigInteger('stan_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('userlevel_id');
            $table->boolean('status')->default(false);
            $table->timestamps();
            $table->foreign('cafe_id')->references('id')->on('cafes');
            $table->foreign('stan_id')->references('id')->on('stans');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('userlevel_id')->references('id')->on('user_levels');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cafe_management');
    }
};
