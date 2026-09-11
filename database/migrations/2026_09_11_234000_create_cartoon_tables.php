<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cartoons', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->string('original_title')->nullable();
            $table->json('description')->nullable();
            $table->unsignedSmallInteger('year')->nullable()->index();
            $table->string('poster')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('cartoon_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cartoon_id')->constrained()->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->string('filename');
            $table->string('path', 512);
            $table->string('extension', 32)->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique('filename');
        });

        Schema::create('genre_cartoon', function (Blueprint $table) {
            $table->id();
            $table->foreignId('genre_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cartoon_id')->constrained()->cascadeOnDelete();
            $table->unique(['genre_id', 'cartoon_id']);
        });

        Schema::create('country_cartoon', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cartoon_id')->constrained()->cascadeOnDelete();
            $table->unique(['country_id', 'cartoon_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('country_cartoon');
        Schema::dropIfExists('genre_cartoon');
        Schema::dropIfExists('cartoon_files');
        Schema::dropIfExists('cartoons');
    }
};
