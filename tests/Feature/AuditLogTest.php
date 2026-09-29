<?php

namespace Tests\Feature;

use App\Enums\ArticleStatus;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_editorial_operations_are_audited(): void
    {
        $user = User::factory()->create(['role' => 'editor']);
        $this->actingAs($user);

        $category = Category::create(['name' => 'Economia', 'slug' => 'economia', 'color' => '#0b5c47']);

        $article = Article::create([
            'author_id' => $user->id, 'category_id' => $category->id,
            'title' => 'A', 'slug' => 'a', 'status' => ArticleStatus::Draft->value,
        ]);
        $this->assertDatabaseHas('audit_logs', ['auditable_id' => $article->id, 'action' => 'created']);

        $article->update(['status' => ArticleStatus::Published->value, 'published_at' => now()]);
        $this->assertDatabaseHas('audit_logs', ['auditable_id' => $article->id, 'action' => 'status_changed']);

        $id = $article->id;
        $article->delete();
        $this->assertDatabaseHas('audit_logs', ['auditable_id' => $id, 'action' => 'deleted']);
    }
}
