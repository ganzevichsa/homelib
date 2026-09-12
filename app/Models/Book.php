<?php

namespace App\Models;

use Database\Factories\BookFactory;
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
    'author',
    'original_title',
    'description',
    'year',
    'isbn',
    'poster',
])]
#[Translatable('title', 'description')]
class Book extends Model
{
    /** @use HasFactory<BookFactory> */
    use HasFactory, HasTranslations, SoftDeletes;

    protected static function booted(): void
    {
        static::deleting(function (Book $book): void {
            $book->files()->delete();
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
     * @return HasMany<BookFile, $this>
     */
    public function files(): HasMany
    {
        return $this->hasMany(BookFile::class)->orderBy('sort_order')->orderBy('id');
    }

    /**
     * @return BelongsToMany<Genre, $this>
     */
    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'genre_book')->orderBy('genres.name');
    }

    public function hasPoster(): bool
    {
        return filled($this->poster)
            && Storage::disk((string) config('media.disk'))->exists($this->poster);
    }

    public function storePoster(UploadedFile $file): void
    {
        $this->deletePosterFile();

        $filename = 'book-'.$this->id.'.'.strtolower($file->getClientOriginalExtension());
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
