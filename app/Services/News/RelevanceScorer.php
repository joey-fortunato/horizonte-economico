<?php

namespace App\Services\News;

use Carbon\CarbonInterface;
use Illuminate\Support\Str;

class RelevanceScorer
{
    /**
     * Pontua uma notícia face à linha editorial.
     *
     * @return array{score:int, terms:array<int,string>}
     */
    public function score(string $title, ?string $description, ?CarbonInterface $publishedAt): array
    {
        $text = $this->normalize($title.' '.($description ?? ''));
        $score = 0;
        $terms = [];

        foreach ((array) config('editorial.keywords', []) as $keyword => $weight) {
            if ($this->contains($text, $keyword)) {
                $score += (int) $weight;
                $terms[] = $keyword;
            }
        }

        foreach ((array) config('editorial.negative', []) as $keyword => $weight) {
            if ($this->contains($text, $keyword)) {
                $score -= (int) $weight;
            }
        }

        // Bónus de actualidade
        if ($publishedAt && $publishedAt->gte(now()->subDays((int) config('editorial.recency_days', 3)))) {
            $score += (int) config('editorial.recency_bonus', 3);
        }

        return ['score' => max(0, $score), 'terms' => $terms];
    }

    public function isHighlight(int $score): bool
    {
        return $score >= (int) config('editorial.highlight_threshold', 8);
    }

    private function contains(string $normalizedText, string $keyword): bool
    {
        $kw = preg_quote($this->normalize($keyword), '/');

        return (bool) preg_match('/\b'.$kw.'\b/u', $normalizedText);
    }

    private function normalize(string $text): string
    {
        return Str::lower(Str::ascii($text));
    }
}
