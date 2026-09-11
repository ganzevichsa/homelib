<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('animated_series', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->string('original_title')->nullable();
            $table->json('description')->nullable();
            $table->unsignedSmallInteger('year')->nullable()->index();
            $table->string('poster')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('animated_seasons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animated_series_id')->constrained('animated_series')->cascadeOnDelete();
            $table->unsignedSmallInteger('number');
            $table->string('title')->nullable();
            $table->timestamps();

            $table->unique(['animated_series_id', 'number']);
        });

        Schema::create('animated_episodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animated_season_id')->constrained('animated_seasons')->cascadeOnDelete();
            $table->unsignedSmallInteger('number');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('filename');
            $table->string('path', 512);
            $table->string('extension', 32)->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->timestamps();

            $table->unique(['animated_season_id', 'number']);
            $table->unique('filename');
        });

        Schema::create('genre_animated_series', function (Blueprint $table) {
            $table->id();
            $table->foreignId('genre_id')->constrained()->cascadeOnDelete();
            $table->foreignId('animated_series_id')->constrained('animated_series')->cascadeOnDelete();
            $table->unique(['genre_id', 'animated_series_id']);
        });

        Schema::create('country_animated_series', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->foreignId('animated_series_id')->constrained('animated_series')->cascadeOnDelete();
            $table->unique(['country_id', 'animated_series_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('country_animated_series');
        Schema::dropIfExists('genre_animated_series');
        Schema::dropIfExists('animated_episodes');
        Schema::dropIfExists('animated_seasons');
        Schema::dropIfExists('animated_series');
    }
};
