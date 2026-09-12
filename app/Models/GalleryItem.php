<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['gallery_album_id', 'title', 'filename', 'path', 'extension', 'mime_type', 'size', 'sort_order'])]
class GalleryItem extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<GalleryAlbum, $this>
     */
    public function album(): BelongsTo
    {
        return $this->belongsTo(GalleryAlbum::class, 'gallery_album_id');
    }

    public function isImage(): bool
    {
        return in_array($this->extension, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true);
    }

    public function isVideo(): bool
    {
        return in_array($this->extension, ['mp4', 'webm', 'mov', 'm4v', 'mkv', 'avi'], true);
    }

    public function isBrowserPlayable(): bool
    {
        return $this->isImage() || in_array($this->extension, ['mp4', 'webm', 'm4v'], true);
    }

    public function browserMime(): string
    {
        return match ($this->extension) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
            'gif' => 'image/gif',
            'mp4', 'm4v' => 'video/mp4',
            'webm' => 'video/webm',
            'mov' => 'video/quicktime',
            'mkv' => 'video/x-matroska',
            'avi' => 'video/x-msvideo',
            default => $this->mime_type ?: 'application/octet-stream',
        };
    }

    public function label(): string
    {
        return $this->title ?: $this->filename;
    }
}
