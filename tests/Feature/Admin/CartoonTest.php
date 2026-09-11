<?php

namespace Tests\Feature\Admin;

use App\Models\Cartoon;
use App\Models\Country;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CartoonTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_cartoon_card_without_files(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.cartoons.store'), [
                'title' => 'Король Лев',
                'year' => 1994,
            ])
            ->assertRedirect();

        $cartoon = Cartoon::query()->first();

        $this->assertNotNull($cartoon);
        $this->assertSame('Король Лев', $cartoon->getTranslation('title', 'ru'));
        $this->assertSame(0, $cartoon->files()->count());

        $this->actingAs($user)
            ->get(route('admin.cartoons.edit', $cartoon))
            ->assertOk()
            ->assertSee('Король Лев')
            ->assertSee('Файлов пока нет');
    }

    public function test_admin_adds_cartoon_files_one_by_one(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('cartoons/Lion.King.1994.mkv', 'one');
        Storage::disk('media')->put('cartoons/Lion.King.2.mkv', 'two');

        $user = User::factory()->create();
        $cartoon = Cartoon::factory()->create([
            'title' => ['ru' => 'Король Лев'],
        ]);

        $this->actingAs($user)
            ->post(route('admin.cartoons.files.store', $cartoon), [
                'title' => 'Король Лев (1994)',
                'year' => 1994,
                'description' => 'Первая часть.',
                'filename' => 'Lion.King.1994.mkv',
            ])
            ->assertRedirect(route('admin.cartoons.edit', $cartoon));

        $this->actingAs($user)
            ->post(route('admin.cartoons.files.store', $cartoon), [
                'title' => 'Король Лев 2',
                'year' => 1998,
                'filename' => 'Lion.King.2.mkv',
            ])
            ->assertRedirect(route('admin.cartoons.edit', $cartoon));

        $this->assertSame(2, $cartoon->files()->count());
        $this->assertDatabaseHas('cartoon_files', [
            'cartoon_id' => $cartoon->id,
            'filename' => 'Lion.King.1994.mkv',
            'title' => 'Король Лев (1994)',
        ]);
    }

    public function test_file_must_exist_in_cartoons_folder(): void
    {
        Storage::fake('media');

        $user = User::factory()->create();
        $cartoon = Cartoon::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.cartoons.files.store', $cartoon), [
                'title' => 'Нет файла',
                'filename' => 'Missing.mkv',
            ])
            ->assertSessionHasErrors('filename');

        $this->assertSame(0, $cartoon->files()->count());
    }

    public function test_admin_can_attach_genres_and_countries(): void
    {
        $user = User::factory()->create();
        $family = Genre::query()->create(['name' => 'Семейный']);
        $usa = Country::query()->create(['name' => 'США']);

        $this->actingAs($user)
            ->post(route('admin.cartoons.store'), [
                'title' => 'Король Лев',
                'genre_ids' => [$family->id],
                'country_ids' => [$usa->id],
            ])
            ->assertRedirect();

        $cartoon = Cartoon::query()->first();

        $this->assertNotNull($cartoon);
        $this->assertEqualsCanonicalizing([$family->id], $cartoon->genres()->pluck('genres.id')->all());
        $this->assertEqualsCanonicalizing([$usa->id], $cartoon->countries()->pluck('countries.id')->all());
    }

    public function test_file_search_returns_matching_unattached_names(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('cartoons/Lion.King.1994.mkv', 'one');
        Storage::disk('media')->put('cartoons/Shrek.2001.mkv', 'two');

        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson(route('admin.cartoons.files.search', ['q' => 'lion']))
            ->assertOk()
            ->assertExactJson([
                'filenames' => ['Lion.King.1994.mkv'],
            ]);
    }

    public function test_admin_can_upload_a_poster(): void
    {
        Storage::fake('media');

        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.cartoons.store'), [
                'title' => 'Король Лев',
                'poster' => UploadedFile::fake()->image('poster.jpg', 400, 600),
            ])
            ->assertRedirect();

        $cartoon = Cartoon::query()->first();

        $this->assertNotNull($cartoon?->poster);
        Storage::disk('media')->assertExists($cartoon->poster);
    }

    public function test_admin_cartoons_index_is_empty(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.cartoons'))
            ->assertOk()
            ->assertSee('Мультфильмы')
            ->assertSee('Пока пусто');
    }
}
