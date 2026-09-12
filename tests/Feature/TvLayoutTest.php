<?php

namespace Tests\Feature;

use Tests\TestCase;

class TvLayoutTest extends TestCase
{
    public function test_tv_app_user_agent_marks_the_public_layout(): void
    {
        $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 HomelibApp/1.0 TV',
        ])->get('/')
            ->assertOk()
            ->assertSee('class="tv"', false);
    }

    public function test_browser_does_not_get_tv_class(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertDontSee('class="tv"', false);
    }
}
