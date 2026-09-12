<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'album_id',
    'number',
    'title',
    'filename',
    'path',
    'extension',
    'mime_type',
    'size',
])]
class AlbumTrack extends Model
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
     * @return BelongsTo<Album, $this>
     */
    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }

    public function isBrowserPlayable(): bool
    {
        return in_array($this->extension, ['mp3', 'm4a', 'aac', 'ogg', 'opus', 'wav', 'flac'], true);
    }

    public function browserMime(): string
    {
        return match ($this->extension) {
            'mp3' => 'audio/mpeg',
            'm4a', 'aac' => 'audio/mp4',
            'ogg', 'opus' => 'audio/ogg',
            'wav' => 'audio/wav',
            'flac' => 'audio/flac',
            default => $this->mime_type ?: 'application/octet-stream',
        };
    }
}
