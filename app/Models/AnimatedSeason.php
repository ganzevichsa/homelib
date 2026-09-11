<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'animated_series_id',
    'number',
    'title',
])]
class AnimatedSeason extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'number' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<AnimatedSeries, $this>
     */
    public function animatedSeries(): BelongsTo
    {
        return $this->belongsTo(AnimatedSeries::class);
    }

    /**
     * @return HasMany<AnimatedEpisode, $this>
     */
    public function episodes(): HasMany
    {
        return $this->hasMany(AnimatedEpisode::class)->orderBy('number');
    }

    public function label(): string
    {
        return $this->title ?: 'Сезон '.$this->number;
    }
}
