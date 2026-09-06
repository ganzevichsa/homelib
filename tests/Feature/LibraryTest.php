<?php

namespace Tests\Feature;

use App\Enums\MediaType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LibraryTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_page_is_empty_placeholder(): void
    {
        $this->get(route('library.show', MediaType::Movie))
            ->assertOk()
            ->assertSee('Фильмы')
            ->assertSee('Пока пусто');
    }
}
