<?php

namespace App\Models;

use App\Models\Concerns\StoresMediaPoster;
use Database\Factories\AudiobookFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Fillable(['title', 'author', 'narrator', 'description', 'year', 'poster'])]
#[Translatable('title', 'description')]
class Audiobook extends Model
{
    /** @use HasFactory<AudiobookFactory> */
    use HasFactory, HasTranslations, SoftDeletes, StoresMediaPoster;

    protected static function booted(): void
    {
        static::deleting(function (Audiobook $audiobook): void {
            $audiobook->chapters()->delete();
        });
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['year' => 'integer'];
    }

    /**
     * @return HasMany<AudiobookChapter, $this>
     */
    public function chapters(): HasMany
    {
        return $this->hasMany(AudiobookChapter::class)->orderBy('number')->orderBy('id');
    }

    protected function posterPrefix(): string
    {
        return 'audiobook';
    }
}
