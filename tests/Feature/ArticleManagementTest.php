<?php

namespace Tests\Feature;

use App\Enums\ArticleStatus;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleManagementTest extends TestCase
{
    use RefreshDatabase;

    private function category(): Category
    {
        return Category::create(['name' => 'Economia', 'slug' => 'economia', 'color' => '#0b5c47']);
    }

    public function test_author_can_save_draft(): void
    {
        $author = User::factory()->create(['role' => 'author']);

        $this->actingAs($author)->post(route('admin.articles.store'), [
            'title' => 'O meu rascunho',
            'status' => ArticleStatus::Draft->value,
            'category_id' => $this->category()->id,
        ])->assertRedirect();

        $this->assertDatabaseHas('articles', [
            'title' => 'O meu rascunho',
            'status' => 'draft',
            'author_id' => $author->id,
        ]);
    }

    public function test_author_cannot_publish_directly(): void
    {
        $author = User::factory()->create(['role' => 'author']);

        $this->actingAs($author)->post(route('admin.articles.store'), [
            'title' => 'Tentativa',
            'status' => ArticleStatus::Published->value,
            'category_id' => $this->category()->id,
        ])->assertForbidden();

        $this->assertDatabaseMissing('articles', ['title' => 'Tentativa']);
    }

    public function test_editor_can_publish(): void
    {
        $editor = User::factory()->create(['role' => 'editor']);

        $this->actingAs($editor)->post(route('admin.articles.store'), [
            'title' => 'Publicado pelo editor',
            'status' => ArticleStatus::Published->value,
            'category_id' => $this->category()->id,
        ])->assertRedirect();

        $article = Article::where('title', 'Publicado pelo editor')->first();
        $this->assertSame(ArticleStatus::Published, $article->status);
        $this->assertNotNull($article->published_at);
    }

    public function test_author_cannot_edit_others_article_via_http(): void
    {
        $author = User::factory()->create(['role' => 'author']);
        $other = User::factory()->create(['role' => 'author']);
        $article = Article::create([
            'author_id' => $other->id,
            'category_id' => $this->category()->id,
            'title' => 'De outro',
            'slug' => 'de-outro',
            'status' => ArticleStatus::Draft->value,
        ]);

        $this->actingAs($author)->put(route('admin.articles.update', $article), [
            'title' => 'Alterado',
            'status' => ArticleStatus::Draft->value,
        ])->assertForbidden();
    }

    public function test_articles_list_loads(): void
    {
        $editor = User::factory()->create(['role' => 'editor']);
        $this->actingAs($editor)->get(route('admin.articles.index'))->assertOk();
    }
}
