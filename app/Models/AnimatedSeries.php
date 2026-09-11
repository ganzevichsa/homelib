<?php

namespace App\Models;

use Database\Factories\AnimatedSeriesFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Fillable([
    'title',
    'original_title',
    'description',
    'year',
    'poster',
])]
#[Translatable('title', 'description')]
class AnimatedSeries extends Model
{
    /** @use HasFactory<AnimatedSeriesFactory> */
    use HasFactory, HasTranslations, SoftDeletes;

    protected static function booted(): void
    {
        static::deleting(function (AnimatedSeries $animatedSeries): void {
            $animatedSeries->seasons->each(function (AnimatedSeason $season): void {
                $season->episodes()->delete();
                $season->delete();
            });
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
     * @return HasMany<AnimatedSeason, $this>
     */
    public function seasons(): HasMany
    {
        return $this->hasMany(AnimatedSeason::class)->orderBy('number');
    }

    /**
     * @return HasManyThrough<AnimatedEpisode, AnimatedSeason, $this>
     */
    public function episodes(): HasManyThrough
    {
        return $this->hasManyThrough(AnimatedEpisode::class, AnimatedSeason::class);
    }

    /**
     * @return BelongsToMany<Genre, $this>
     */
    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'genre_animated_series')->orderBy('genres.name');
    }

    /**
     * @return BelongsToMany<Country, $this>
     */
    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class, 'country_animated_series')->orderBy('countries.name');
    }

    public function hasPoster(): bool
    {
        return filled($this->poster)
            && Storage::disk((string) config('media.disk'))->exists($this->poster);
    }

    public function storePoster(UploadedFile $file): void
    {
        $this->deletePosterFile();

        $filename = 'animated-series-'.$this->id.'.'.strtolower($file->getClientOriginalExtension());
        $path = $file->storeAs((string) config('media.posters.directory'), $filename, (string) config('media.disk'));

        $this->update(['poster' => $path]);
    }

    public function deletePosterFile(): void
    {
        if (filled($this->poster)) {
            Storage::disk((string) config('media.disk'))->delete($this->poster);
        }
    }
}
