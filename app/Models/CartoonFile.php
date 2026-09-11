<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'cartoon_id',
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
class CartoonFile extends Model
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
     * @return BelongsTo<Cartoon, $this>
     */
    public function cartoon(): BelongsTo
    {
        return $this->belongsTo(Cartoon::class);
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
