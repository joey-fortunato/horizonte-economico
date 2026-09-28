<?php

namespace Tests\Feature;

use App\Enums\ArticleStatus;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BackofficeAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_backoffice(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_non_editorial_role_is_forbidden(): void
    {
        $user = User::factory()->create(['role' => 'subscriber', 'email_verified_at' => now()]);
        $this->actingAs($user)->get('/dashboard')->assertForbidden();
    }

    public function test_editorial_role_can_access(): void
    {
        $user = User::factory()->create(['role' => 'editor', 'email_verified_at' => now()]);
        $this->actingAs($user)->get('/dashboard')->assertOk();
    }

    public function test_author_cannot_update_others_article(): void
    {
        $author = User::factory()->create(['role' => 'author']);
        $other = User::factory()->create(['role' => 'author']);
        $category = Category::create(['name' => 'Economia', 'slug' => 'economia', 'color' => '#0b5c47']);

        $article = Article::create([
            'author_id' => $other->id,
            'category_id' => $category->id,
            'title' => 'Outro',
            'slug' => 'outro',
            'status' => ArticleStatus::Draft->value,
        ]);

        $this->assertFalse($author->can('update', $article));
        $this->assertTrue($other->can('update', $article));
    }

    public function test_scheduled_articles_are_published(): void
    {
        $author = User::factory()->create();
        $category = Category::create(['name' => 'Economia', 'slug' => 'economia', 'color' => '#0b5c47']);

        $article = Article::create([
            'author_id' => $author->id,
            'category_id' => $category->id,
            'title' => 'Agendado',
            'slug' => 'agendado',
            'status' => ArticleStatus::Scheduled->value,
            'published_at' => now()->subMinute(),
        ]);

        $this->artisan('articles:publish-scheduled')->assertSuccessful();

        $this->assertSame(ArticleStatus::Published, $article->fresh()->status);
    }
}
