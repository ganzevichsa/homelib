<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->string('artist');
            $table->string('original_title')->nullable();
            $table->json('description')->nullable();
            $table->unsignedSmallInteger('year')->nullable()->index();
            $table->string('poster')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('album_tracks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('number');
            $table->string('title');
            $table->string('filename');
            $table->string('path', 512);
            $table->string('extension', 32)->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->timestamps();

            $table->unique(['album_id', 'number']);
            $table->unique('filename');
        });

        Schema::create('genre_album', function (Blueprint $table) {
            $table->id();
            $table->foreignId('genre_id')->constrained()->cascadeOnDelete();
            $table->foreignId('album_id')->constrained()->cascadeOnDelete();
            $table->unique(['genre_id', 'album_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('genre_album');
        Schema::dropIfExists('album_tracks');
        Schema::dropIfExists('albums');
    }
};
