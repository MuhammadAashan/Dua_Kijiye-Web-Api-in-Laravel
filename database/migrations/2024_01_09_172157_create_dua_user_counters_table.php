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
        Schema::create('dua_user_counters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dua_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('count');
            $table->timestamps();

            // Foreign keys
            $table->foreign('dua_id')->references('id')->on('dua')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dua_user_counters');
    }
};
