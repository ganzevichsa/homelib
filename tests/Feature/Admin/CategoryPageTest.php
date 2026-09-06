<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_category_page_is_empty(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.movies'))
            ->assertOk()
            ->assertSee('Фильмы')
            ->assertSee('Пока пусто');
    }
}
