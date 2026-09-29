<?php

namespace Tests\Feature;

use App\Enums\ArticleStatus;
use App\Models\Article;
use App\Models\Category;
use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_creates_category_editor_cannot(): void
    {
        $admin = User::factory()->create(['role' => 'administrador']);
        $editor = User::factory()->create(['role' => 'editor']);

        $this->actingAs($editor)->post('/categorias', ['name' => 'X', 'color' => '#123b30'])->assertForbidden();

        $this->actingAs($admin)->post('/categorias', ['name' => 'Nova', 'color' => '#123b30'])->assertRedirect();
        $this->assertDatabaseHas('categories', ['name' => 'Nova', 'slug' => 'nova']);
    }

    public function test_category_with_articles_cannot_be_deleted(): void
    {
        $admin = User::factory()->create(['role' => 'administrador']);
        $category = Category::create(['name' => 'Economia', 'slug' => 'economia', 'color' => '#0b5c47']);
        Article::create([
            'author_id' => $admin->id, 'category_id' => $category->id,
            'title' => 'A', 'slug' => 'a', 'status' => ArticleStatus::Draft->value,
        ]);

        $this->actingAs($admin)->delete("/categorias/{$category->slug}")->assertSessionHasErrors('category');
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_admin_invites_user_editor_cannot(): void
    {
        $admin = User::factory()->create(['role' => 'administrador']);
        $editor = User::factory()->create(['role' => 'editor']);

        $this->actingAs($editor)->post('/utilizadores', [
            'name' => 'X', 'email' => 'x@e.co', 'role' => 'author',
        ])->assertForbidden();

        $this->actingAs($admin)->post('/utilizadores', [
            'name' => 'Nova Autora', 'email' => 'nova@he.co', 'role' => 'author',
        ])->assertRedirect();

        $this->assertDatabaseHas('users', ['email' => 'nova@he.co', 'status' => 'invited', 'role' => 'author']);
    }

    public function test_media_upload(): void
    {
        Storage::fake('public');
        $editor = User::factory()->create(['role' => 'editor']);

        $this->actingAs($editor)->post('/media', [
            'file' => UploadedFile::fake()->image('capa.jpg', 1600, 900),
            'alt_text' => 'Capa',
        ])->assertRedirect();

        $this->assertDatabaseCount('media', 1);

        $media = Media::first();
        $this->assertSame('image/webp', $media->mime_type);
        $this->assertNotEmpty($media->variants); // variantes responsivas geradas
    }

    public function test_editor_media_json_endpoints(): void
    {
        Storage::fake('public');
        $editor = User::factory()->create(['role' => 'editor']);

        $this->actingAs($editor)->post('/media/upload', [
            'file' => UploadedFile::fake()->image('a.jpg', 800, 450),
        ])->assertOk()->assertJsonStructure(['id', 'url', 'srcset', 'alt']);

        $this->actingAs($editor)->get('/media/list')->assertOk()->assertJsonCount(1);
    }
}
