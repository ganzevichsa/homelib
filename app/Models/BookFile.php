<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'book_id',
    'title',
    'filename',
    'path',
    'extension',
    'mime_type',
    'size',
    'sort_order',
])]
class BookFile extends Model
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
     * @return BelongsTo<Book, $this>
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function isBrowserReadable(): bool
    {
        return in_array($this->extension, ['pdf', 'txt'], true);
    }

    public function browserMime(): string
    {
        return match ($this->extension) {
            'pdf' => 'application/pdf',
            'txt' => 'text/plain; charset=utf-8',
            'epub' => 'application/epub+zip',
            'fb2' => 'application/x-fictionbook+xml',
            'mobi' => 'application/x-mobipocket-ebook',
            default => $this->mime_type ?: 'application/octet-stream',
        };
    }
}
