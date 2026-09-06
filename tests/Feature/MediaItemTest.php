<?php

namespace Tests\Feature;

use App\Enums\Locale;
use App\Enums\MediaType;
use App\Models\MediaItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MediaItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_media_item_stores_translated_title(): void
    {
        $item = MediaItem::factory()->create([
            'type' => MediaType::Movie,
            'title' => [
                Locale::Ru->value => 'Интерстеллар',
                Locale::En->value => 'Interstellar',
            ],
            'path' => 'movies/Interstellar (2014).mkv',
            'filename' => 'Interstellar (2014).mkv',
        ]);

        $this->assertSame(MediaType::Movie, $item->type);
        $this->assertSame('movies', $item->type->directory());
        $this->assertSame('Интерстеллар', $item->getTranslation('title', Locale::Ru->value));
        $this->assertSame('Interstellar', $item->getTranslation('title', Locale::En->value));
    }
}
