<?php

namespace Tests\Feature;

use App\Enums\ArticleStatus;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    private function makeArticle(array $overrides = []): Article
    {
        $author = User::factory()->create(['slug' => 'autor-'.uniqid()]);
        $category = Category::firstOrCreate(
            ['slug' => 'economia'],
            ['name' => 'Economia', 'color' => '#0b5c47', 'is_active' => true],
        );

        return Article::create(array_merge([
            'author_id' => $author->id,
            'category_id' => $category->id,
            'title' => 'Artigo de teste',
            'slug' => 'artigo-de-teste-'.uniqid(),
            'excerpt' => 'Resumo de teste.',
            'body' => '<p>Corpo.</p>',
            'status' => ArticleStatus::Published->value,
            'published_at' => now()->subDay(),
            'reading_minutes' => 5,
        ], $overrides));
    }

    public function test_homepage_is_visible_to_guest(): void
    {
        $this->makeArticle();
        $this->get('/')->assertOk();
    }

    public function test_published_article_opens(): void
    {
        $a = $this->makeArticle(['title' => 'Inflação em Angola', 'slug' => 'inflacao-angola']);
        $this->get(route('artigo', $a->slug))->assertOk()->assertSee('Inflação em Angola');
    }

    public function test_unpublished_article_returns_404(): void
    {
        $a = $this->makeArticle(['status' => ArticleStatus::Draft->value, 'published_at' => null, 'slug' => 'rascunho']);
        $this->get(route('artigo', $a->slug))->assertNotFound();
    }

    public function test_category_browsing(): void
    {
        $this->makeArticle();
        $this->get(route('categoria', 'economia'))->assertOk()->assertSee('Economia');
    }

    public function test_search_returns_results(): void
    {
        $this->makeArticle(['title' => 'Guia do orçamento familiar', 'slug' => 'guia-orcamento']);
        $this->get('/pesquisa?q=orçamento')->assertOk()->assertSee('familiar');
    }

    public function test_sitemap_and_robots(): void
    {
        $this->makeArticle();
        $this->get('/sitemap.xml')->assertOk()->assertHeader('Content-Type', 'application/xml');
        $this->get('/robots.txt')->assertOk();
    }
}
