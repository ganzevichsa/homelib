<?php

namespace App\Models;

use Database\Factories\SeriesFactory;
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
class Series extends Model
{
    /** @use HasFactory<SeriesFactory> */
    use HasFactory, HasTranslations, SoftDeletes;

    protected static function booted(): void
    {
        static::deleting(function (Series $series): void {
            $series->seasons->each(function (Season $season): void {
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
     * @return HasMany<Season, $this>
     */
    public function seasons(): HasMany
    {
        return $this->hasMany(Season::class)->orderBy('number');
    }

    /**
     * @return HasManyThrough<Episode, Season, $this>
     */
    public function episodes(): HasManyThrough
    {
        return $this->hasManyThrough(Episode::class, Season::class);
    }

    /**
     * @return BelongsToMany<Genre, $this>
     */
    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class)->orderBy('genres.name');
    }

    /**
     * @return BelongsToMany<Country, $this>
     */
    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class)->orderBy('countries.name');
    }

    public function hasPoster(): bool
    {
        return filled($this->poster)
            && Storage::disk((string) config('media.disk'))->exists($this->poster);
    }

    public function storePoster(UploadedFile $file): void
    {
        $this->deletePosterFile();

        $filename = 'series-'.$this->id.'.'.strtolower($file->getClientOriginalExtension());
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
