<?php

namespace Tests\Feature\Admin;

use App\Models\Credential;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CredentialTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_open_passwords(): void
    {
        $this->get(route('admin.credentials'))
            ->assertRedirect(route('login'));
    }

    public function test_admin_can_create_encrypted_password(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.credentials.store'), [
                'title' => 'подключение на сайт ЕРА админ',
                'login' => 'root',
                'password' => '123',
                'ip' => '192.168.2.1',
                'protocol' => 'ssh',
            ])
            ->assertRedirect();

        $credential = Credential::query()->first();

        $this->assertNotNull($credential);
        $this->assertSame('подключение на сайт ЕРА админ', $credential->title);
        $this->assertSame('root', $credential->login);
        $this->assertSame('123', $credential->password);
        $this->assertSame('192.168.2.1', $credential->ip);
        $this->assertSame('ssh', $credential->protocol);

        $raw = DB::table('credentials')->where('id', $credential->id)->value('password');
        $this->assertNotSame('123', $raw);
    }

    public function test_admin_passwords_index_is_empty(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.credentials'))
            ->assertOk()
            ->assertSee('Пароли')
            ->assertSee('Пока пусто');
    }
}
