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
        Schema::create('table_info', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cafe_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name');
            $table->boolean('status')->default(false);
            $table->uuid('uuid');
            $table->timestamps();
            $table->foreign('cafe_id')->references('id')->on('cafes')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_info');
    }
};
