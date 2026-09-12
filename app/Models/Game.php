<?php

namespace App\Models;

use App\Models\Concerns\StoresMediaPoster;
use Database\Factories\GameFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Fillable(['title', 'description', 'year', 'poster'])]
#[Translatable('title', 'description')]
class Game extends Model
{
    /** @use HasFactory<GameFactory> */
    use HasFactory, HasTranslations, SoftDeletes, StoresMediaPoster;

    protected static function booted(): void
    {
        static::deleting(function (Game $game): void {
            $game->files()->delete();
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
     * @return HasMany<GameFile, $this>
     */
    public function files(): HasMany
    {
        return $this->hasMany(GameFile::class)->orderBy('sort_order')->orderBy('id');
    }

    protected function posterPrefix(): string
    {
        return 'game';
    }
}
