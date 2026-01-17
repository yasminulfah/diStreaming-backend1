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
        Schema::create('movies', function (Blueprint $table) {
            $table->integer('movie_id')->primary()->autoIncrement();
            $table->string('title', 250);
            $table->integer('release_year');
            $table->integer('duration');
            $table->integer('director_id');
            $table->foreign('director_id')->references('director_id')->on('directors')->onDelete('restrict');
            $table->integer('category_id');
            $table->foreign('category_id')->references('category_id')->on('categories')->onDelete('restrict');
            $table->text('description')->nullable();
            $table->string('poster_url', 250)->nullable();
            $table->string('language', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
