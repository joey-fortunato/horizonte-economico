<?php

namespace Tests\Feature;

use App\Enums\NewsEditorialStatus;
use App\Enums\NewsFetchStatus;
use App\Models\CollectedNews;
use App\Models\NewsSource;
use App\Services\News\NewsCollector;
use App\Services\News\UrlNormalizer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class NewsCollectionTest extends TestCase
{
    use RefreshDatabase;

    private function feed(): string
    {
        return <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0"><channel><title>Feed</title>
<item><title>Notícia Um</title><link>https://exemplo.co.ao/artigo-1?utm_source=x</link><guid>guid-1</guid><description>Resumo &lt;b&gt;um&lt;/b&gt;</description><pubDate>Mon, 29 Sep 2026 10:00:00 +0000</pubDate></item>
<item><title>Notícia Dois</title><link>https://exemplo.co.ao/artigo-2</link><guid>guid-2</guid></item>
</channel></rss>
XML;
    }

    private function source(): NewsSource
    {
        return NewsSource::create([
            'name' => 'Feed de teste',
            'url' => 'https://feed.exemplo.co.ao/rss',
            'type' => 'rss',
            'is_active' => true,
        ]);
    }

    public function test_collects_valid_feed(): void
    {
        Http::fake(['*' => Http::response($this->feed(), 200)]);

        $run = app(NewsCollector::class)->collect($this->source());

        $this->assertSame(NewsFetchStatus::Completed, $run->status);
        $this->assertSame(2, $run->items_found);
        $this->assertSame(2, $run->items_created);
        $this->assertSame(2, CollectedNews::count());
        $this->assertSame(NewsEditorialStatus::Pending, CollectedNews::first()->editorial_status);
        // conteúdo externo sanitizado (sem HTML)
        $this->assertStringNotContainsString('<b>', (string) CollectedNews::where('external_id', 'guid-1')->value('description'));
    }

    public function test_deduplicates_on_recollect(): void
    {
        Http::fake(['*' => Http::response($this->feed(), 200)]);
        $source = $this->source();

        app(NewsCollector::class)->collect($source);
        $second = app(NewsCollector::class)->collect($source);

        $this->assertSame(0, $second->items_created);
        $this->assertSame(2, CollectedNews::count());
    }

    public function test_invalid_feed_marks_run_failed(): void
    {
        Http::fake(['*' => Http::response('isto não é xml', 200)]);

        $run = app(NewsCollector::class)->collect($this->source());

        $this->assertSame(NewsFetchStatus::Failed, $run->status);
        $this->assertNotNull($run->error_message);
        $this->assertSame(0, CollectedNews::count());
    }

    public function test_command_collects_active_sources_only(): void
    {
        Http::fake(['*' => Http::response($this->feed(), 200)]);
        $this->source();
        NewsSource::create(['name' => 'Inactiva', 'url' => 'https://x.co/rss', 'type' => 'rss', 'is_active' => false]);

        $this->artisan('news:collect')->assertSuccessful();

        $this->assertSame(2, CollectedNews::count()); // só a fonte activa
    }

    public function test_url_normalizer(): void
    {
        $n = new UrlNormalizer();
        $this->assertSame('https://exemplo.co.ao/a', $n->normalize('HTTPS://Exemplo.CO.AO/a/?utm_source=x&utm_medium=y'));
        $this->assertSame('https://exemplo.co.ao/a?b=1', $n->normalize('https://exemplo.co.ao/a?b=1#frag'));
    }
}
