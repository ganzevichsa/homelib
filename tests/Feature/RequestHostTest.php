<?php

namespace Tests\Feature;

use Tests\TestCase;

class RequestHostTest extends TestCase
{
    public function test_public_links_follow_the_request_host(): void
    {
        $this->get('http://192.168.2.10/')
            ->assertOk()
            ->assertSee('http://192.168.2.10/library/movie', false)
            ->assertDontSee('homelib.test', false);
    }
}
