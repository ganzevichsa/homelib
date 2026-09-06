<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('media_items');

        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->string('original_title')->nullable();
            $table->json('description')->nullable();
            $table->unsignedSmallInteger('year')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('movie_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained()->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->string('filename');
            $table->string('path', 512);
            $table->string('extension', 32)->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->string('hash', 64)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique('filename');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movie_files');
        Schema::dropIfExists('movies');
    }
};
