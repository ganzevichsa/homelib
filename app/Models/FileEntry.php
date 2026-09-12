<?php

namespace App\Models;

use Database\Factories\FileEntryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Fillable(['title', 'description'])]
#[Translatable('title', 'description')]
class FileEntry extends Model
{
    /** @use HasFactory<FileEntryFactory> */
    use HasFactory, HasTranslations, SoftDeletes;

    protected static function booted(): void
    {
        static::deleting(function (FileEntry $entry): void {
            $entry->files()->delete();
        });
    }

    /**
     * @return HasMany<FileEntryFile, $this>
     */
    public function files(): HasMany
    {
        return $this->hasMany(FileEntryFile::class)->orderBy('sort_order')->orderBy('id');
    }
}
