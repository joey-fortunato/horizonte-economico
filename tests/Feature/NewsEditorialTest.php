<?php

namespace Tests\Feature;

use App\Enums\ArticleStatus;
use App\Enums\NewsEditorialStatus;
use App\Models\CollectedNews;
use App\Models\NewsSource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class NewsEditorialTest extends TestCase
{
    use RefreshDatabase;

    private function news(array $overrides = []): CollectedNews
    {
        $source = NewsSource::create(['name' => 'Fonte', 'url' => 'https://f.co/rss', 'type' => 'rss']);

        return CollectedNews::create(array_merge([
            'news_source_id' => $source->id,
            'title' => 'BNA reduz taxa diretora',
            'url' => 'https://exemplo.co.ao/bna',
            'normalized_url' => 'https://exemplo.co.ao/bna',
            'external_id' => 'guid-'.uniqid(),
            'description' => 'Resumo da notícia.',
            'fetched_at' => now(),
            'editorial_status' => NewsEditorialStatus::Pending->value,
        ], $overrides));
    }

    public function test_index_loads_for_editor(): void
    {
        $editor = User::factory()->create(['role' => 'editor']);
        $this->news();
        $this->actingAs($editor)->get(route('admin.news.index'))->assertOk();
    }

    public function test_editor_can_select_author_cannot(): void
    {
        $news = $this->news();

        $author = User::factory()->create(['role' => 'author']);
        $this->actingAs($author)->post(route('admin.news.decide', $news), ['decision' => 'select'])->assertForbidden();

        $editor = User::factory()->create(['role' => 'editor']);
        $this->actingAs($editor)->post(route('admin.news.decide', $news), ['decision' => 'select'])->assertRedirect();
        $this->assertSame(NewsEditorialStatus::Selected, $news->fresh()->editorial_status);
    }

    public function test_editor_adds_note(): void
    {
        $news = $this->news();
        $editor = User::factory()->create(['role' => 'editor']);

        $this->actingAs($editor)->post(route('admin.news.note', $news), ['note' => 'Verificar fonte oficial.'])->assertRedirect();
        $this->assertDatabaseHas('news_editorial_notes', ['collected_news_id' => $news->id, 'note' => 'Verificar fonte oficial.']);
    }

    public function test_convert_creates_draft_and_association(): void
    {
        $news = $this->news(['editorial_status' => NewsEditorialStatus::Selected->value]);
        $editor = User::factory()->create(['role' => 'editor']);

        $this->actingAs($editor)->post(route('admin.news.convert', $news))->assertRedirect();

        $this->assertDatabaseHas('articles', [
            'title' => 'BNA reduz taxa diretora',
            'status' => ArticleStatus::Draft->value,
        ]);
        $this->assertDatabaseHas('article_news_sources', ['collected_news_id' => $news->id]);
        $this->assertSame(NewsEditorialStatus::Drafting, $news->fresh()->editorial_status);
    }

    public function test_rejected_news_cannot_be_converted(): void
    {
        $news = $this->news(['editorial_status' => NewsEditorialStatus::Rejected->value]);
        $editor = User::factory()->create(['role' => 'editor']);

        $this->actingAs($editor)->post(route('admin.news.convert', $news))->assertSessionHasErrors('convert');
        $this->assertDatabaseMissing('articles', ['title' => 'BNA reduz taxa diretora']);
    }

    public function test_manual_collect_requires_admin(): void
    {
        Http::fake(['*' => Http::response('<?xml version="1.0"?><rss version="2.0"><channel><item><title>N</title><link>https://x.co/a</link><guid>g1</guid></item></channel></rss>', 200)]);
        NewsSource::create(['name' => 'Activa', 'url' => 'https://a.co/rss', 'type' => 'rss', 'is_active' => true]);

        $editor = User::factory()->create(['role' => 'editor']);
        $this->actingAs($editor)->post(route('admin.news.collect'))->assertForbidden();

        $admin = User::factory()->create(['role' => 'administrador']);
        $this->actingAs($admin)->post(route('admin.news.collect'))->assertRedirect();
        $this->assertGreaterThan(0, CollectedNews::count());
    }
}
