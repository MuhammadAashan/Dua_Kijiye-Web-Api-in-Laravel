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
        Schema::create('remainders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();;
            $table->unsignedBigInteger('dua_id')->nullable();
            $table->string('category')->nullable();
            $table->dateTime('remainder')->nullable();

            // Define foreign key relationships
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('dua_id')->references('id')->on('dua')->onDelete('set null'); // Assuming set null on delete

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remainders');
    }
};
