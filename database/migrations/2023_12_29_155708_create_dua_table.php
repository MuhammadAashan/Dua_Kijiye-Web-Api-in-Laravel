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
        Schema::create('dua', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->json('dua_name')->nullable(); // Assuming dua_name can be a string
            $table->string('audiolink')->nullable();
            $table->text('urdu_translation')->nullable();
            $table->text('english_translation')->nullable();
            $table->text('arabic_translation')->nullable();
            $table->text('transliteration')->nullable();

            // Define foreign key relationship with the users table

            $table->foreign('category_id')->references('id')->on('category')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dua');
    }
};
