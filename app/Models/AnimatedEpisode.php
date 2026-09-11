<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'animated_season_id',
    'number',
    'title',
    'description',
    'filename',
    'path',
    'extension',
    'mime_type',
    'size',
])]
class AnimatedEpisode extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'number' => 'integer',
            'size' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<AnimatedSeason, $this>
     */
    public function season(): BelongsTo
    {
        return $this->belongsTo(AnimatedSeason::class, 'animated_season_id');
    }

    public function isBrowserPlayable(): bool
    {
        return in_array($this->extension, ['mp4', 'webm', 'm4v'], true);
    }

    public function browserMime(): string
    {
        return match ($this->extension) {
            'mp4', 'm4v' => 'video/mp4',
            'webm' => 'video/webm',
            'mov' => 'video/quicktime',
            'mkv' => 'video/x-matroska',
            'avi' => 'video/x-msvideo',
            default => $this->mime_type ?: 'application/octet-stream',
        };
    }

    public function belongsToAnimatedSeries(AnimatedSeries $animatedSeries): bool
    {
        return $this->season?->animated_series_id === $animatedSeries->id;
    }
}
