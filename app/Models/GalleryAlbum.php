<?php

namespace App\Models;

use App\Models\Concerns\StoresMediaPoster;
use Database\Factories\GalleryAlbumFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Fillable(['title', 'description', 'year', 'poster'])]
#[Translatable('title', 'description')]
class GalleryAlbum extends Model
{
    /** @use HasFactory<GalleryAlbumFactory> */
    use HasFactory, HasTranslations, SoftDeletes, StoresMediaPoster;

    protected static function booted(): void
    {
        static::deleting(function (GalleryAlbum $album): void {
            $album->items()->delete();
        });
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['year' => 'integer'];
    }

    /**
     * @return HasMany<GalleryItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(GalleryItem::class)->orderBy('sort_order')->orderBy('id');
    }

    public function coverItem(): ?GalleryItem
    {
        return $this->items->first(fn (GalleryItem $item): bool => $item->isImage());
    }

    protected function posterPrefix(): string
    {
        return 'gallery';
    }
}
