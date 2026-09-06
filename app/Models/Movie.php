<?php

namespace App\Models;

use Database\Factories\MovieFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Fillable([
    'title',
    'original_title',
    'description',
    'year',
])]
#[Translatable('title', 'description')]
class Movie extends Model
{
    /** @use HasFactory<MovieFactory> */
    use HasFactory, HasTranslations, SoftDeletes;

    protected static function booted(): void
    {
        static::deleting(function (Movie $movie): void {
            $movie->files()->delete();
        });
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'year' => 'integer',
        ];
    }

    /**
     * @return HasMany<MovieFile, $this>
     */
    public function files(): HasMany
    {
        return $this->hasMany(MovieFile::class)->orderBy('sort_order')->orderBy('id');
    }
}
