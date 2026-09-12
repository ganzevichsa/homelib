<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['file_entry_id', 'title', 'filename', 'path', 'extension', 'mime_type', 'size', 'sort_order'])]
class FileEntryFile extends Model
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
     * @return BelongsTo<FileEntry, $this>
     */
    public function fileEntry(): BelongsTo
    {
        return $this->belongsTo(FileEntry::class);
    }

    public function isBrowserReadable(): bool
    {
        return in_array($this->extension, ['pdf', 'txt'], true);
    }

    public function browserMime(): string
    {
        return match ($this->extension) {
            'pdf' => 'application/pdf',
            'txt', 'csv' => 'text/plain; charset=utf-8',
            default => $this->mime_type ?: 'application/octet-stream',
        };
    }
}
