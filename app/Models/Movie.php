<?php

namespace App\Models;

use Database\Factories\MovieFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

        $filename = $this->id.'.'.strtolower($file->getClientOriginalExtension());
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
