<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_items', function (Blueprint $table) {
            $table->id();
            $table->string('type')->index();
            $table->json('title');
            $table->string('original_title')->nullable();
            $table->json('description')->nullable();
            $table->string('path', 512)->unique();
            $table->string('filename');
            $table->string('mime_type')->nullable();
            $table->string('extension', 32)->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->string('hash', 64)->nullable()->index();
            $table->string('thumbnail_path', 512)->nullable();
            $table->unsignedSmallInteger('year')->nullable()->index();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_items');
    }
};
