<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'movie_id',
    'title',
    'description',
    'year',
    'filename',
    'path',
    'extension',
    'mime_type',
    'size',
    'sort_order',
])]
class MovieFile extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'size' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Movie, $this>
     */
    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
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
}
