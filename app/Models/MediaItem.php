<?php

namespace App\Models;

use App\Enums\MediaType;
use Database\Factories\MediaItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Fillable([
    'type',
    'title',
    'original_title',
    'description',
    'path',
    'filename',
    'mime_type',
    'extension',
    'size',
    'hash',
    'thumbnail_path',
    'year',
    'metadata',
])]
#[Translatable('title', 'description')]
class MediaItem extends Model
{
    /** @use HasFactory<MediaItemFactory> */
    use HasFactory, HasTranslations, SoftDeletes;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => MediaType::class,
            'size' => 'integer',
            'year' => 'integer',
            'metadata' => 'array',
        ];
    }
}
