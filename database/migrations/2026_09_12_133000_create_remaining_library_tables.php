<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audiobooks', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->string('author');
            $table->string('narrator')->nullable();
            $table->json('description')->nullable();
            $table->unsignedSmallInteger('year')->nullable()->index();
            $table->string('poster')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('audiobook_chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audiobook_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('number');
            $table->string('title');
            $table->string('filename');
            $table->string('path', 512);
            $table->string('extension', 32)->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->timestamps();

            $table->unique(['audiobook_id', 'number']);
            $table->unique('filename');
        });

        Schema::create('file_entries', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->json('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('file_entry_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_entry_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('filename');
            $table->string('path', 512);
            $table->string('extension', 32)->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique('filename');
        });

        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->json('description')->nullable();
            $table->unsignedSmallInteger('year')->nullable()->index();
            $table->string('poster')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('game_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('filename');
            $table->string('path', 512);
            $table->string('extension', 32)->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique('filename');
        });

        Schema::create('gallery_albums', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->json('description')->nullable();
            $table->unsignedSmallInteger('year')->nullable()->index();
            $table->string('poster')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_album_id')->constrained()->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->string('filename');
            $table->string('path', 512);
            $table->string('extension', 32)->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique('filename');
        });

        Schema::create('credentials', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('login')->nullable();
            $table->text('password')->nullable();
            $table->string('url')->nullable();
            $table->string('ip')->nullable();
            $table->string('protocol')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credentials');
        Schema::dropIfExists('gallery_items');
        Schema::dropIfExists('gallery_albums');
        Schema::dropIfExists('game_files');
        Schema::dropIfExists('games');
        Schema::dropIfExists('file_entry_files');
        Schema::dropIfExists('file_entries');
        Schema::dropIfExists('audiobook_chapters');
        Schema::dropIfExists('audiobooks');
    }
};
