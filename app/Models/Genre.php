<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name'])]
class Genre extends Model
{
    /**
     * @return BelongsToMany<Movie, $this>
     */
    public function movies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class);
    }

    /**
     * @return BelongsToMany<Series, $this>
     */
    public function series(): BelongsToMany
    {
        return $this->belongsToMany(Series::class);
    }

    /**
     * @return BelongsToMany<Cartoon, $this>
     */
    public function cartoons(): BelongsToMany
    {
        return $this->belongsToMany(Cartoon::class, 'genre_cartoon');
    }

    /**
     * @return BelongsToMany<AnimatedSeries, $this>
     */
    public function animatedSeries(): BelongsToMany
    {
        return $this->belongsToMany(AnimatedSeries::class, 'genre_animated_series');
    }

    /**
     * @return BelongsToMany<Album, $this>
     */
    public function albums(): BelongsToMany
    {
        return $this->belongsToMany(Album::class, 'genre_album');
    }

    /**
     * @return BelongsToMany<Book, $this>
     */
    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'genre_book');
    }
}
