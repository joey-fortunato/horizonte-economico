<?php

namespace Tests\Feature;

use App\Enums\NewsEditorialStatus;
use App\Models\CollectedNews;
use App\Models\NewsSource;
use App\Models\User;
use App\Services\News\RelevanceScorer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsRelevanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_scores_relevant_higher_than_irrelevant(): void
    {
        $scorer = app(RelevanceScorer::class);

        $relevant = $scorer->score('BNA reduz taxa diretora e inflação em Angola abranda', 'kwanza', now());
        $irrelevant = $scorer->score('Resultados do futebol no fim de semana', null, now());

        $this->assertGreaterThan($irrelevant['score'], $relevant['score']);
        $this->assertContains('inflação', $relevant['terms']);
        $this->assertTrue($scorer->isHighlight($relevant['score']));
        $this->assertFalse($scorer->isHighlight($irrelevant['score']));
    }

    public function test_accent_insensitive_matching(): void
    {
        $scorer = app(RelevanceScorer::class);
        // "inflacao" sem acento deve corresponder ao termo "inflação"
        $r = $scorer->score('Inflacao volta a subir em Angola', null, null);
        $this->assertContains('inflação', $r['terms']);
    }

    public function test_highlights_filter_on_index(): void
    {
        $source = NewsSource::create(['name' => 'F', 'url' => 'https://f.co/rss', 'type' => 'rss']);
        $scorer = app(RelevanceScorer::class);

        foreach ([
            'BNA reduz taxa diretora e inflação em Angola' => 'rel',
            'Ténis: final decidida ao quinto set' => 'irrel',
        ] as $title => $tag) {
            $r = $scorer->score($title, null, now());
            CollectedNews::create([
                'news_source_id' => $source->id,
                'title' => $title,
                'url' => 'https://x.co/'.$tag,
                'normalized_url' => 'https://x.co/'.$tag,
                'fetched_at' => now(),
                'editorial_status' => NewsEditorialStatus::Pending->value,
                'relevance_score' => $r['score'],
                'relevance_terms' => $r['terms'],
            ]);
        }

        $editor = User::factory()->create(['role' => 'editor']);
        $this->actingAs($editor)
            ->get(route('admin.news.index', ['highlights' => 1]))
            ->assertOk();

        $this->assertSame(1, CollectedNews::highlights()->count());
    }
}
