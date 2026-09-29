<?php

namespace App\Services\News;

use App\Models\NewsSource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Laminas\Feed\Reader\Reader;

class FeedFetcher
{
    /**
     * Obtém e normaliza os itens de um feed RSS/Atom.
     *
     * @return array<int, array{external_id:?string,title:string,url:string,description:?string,published_at:?Carbon,source_name:?string}>
     *
     * @throws \RuntimeException em caso de falha de acesso ou parsing
     */
    public function fetch(NewsSource $source): array
    {
        $response = Http::timeout(12)
            ->withHeaders(['User-Agent' => 'HorizonteEconomico/1.0 (+https://horizonteeconomico.com)'])
            ->get($source->url);

        if (! $response->successful()) {
            throw new \RuntimeException("HTTP {$response->status()} ao obter o feed.");
        }

        try {
            $feed = Reader::importString($response->body());
        } catch (\Throwable $e) {
            throw new \RuntimeException('Feed inválido: '.$e->getMessage());
        }

        $isGoogleNews = $source->type === 'google_news_rss';
        $items = [];

        foreach ($feed as $entry) {
            $link = trim((string) $entry->getLink());
            if ($link === '') {
                continue;
            }

            $title = trim((string) $entry->getTitle());
            $sourceName = null;

            // Google News: título vem como "Manchete - Fonte"; separa a fonte.
            if ($isGoogleNews && str_contains($title, ' - ')) {
                $pos = mb_strrpos($title, ' - ');
                $sourceName = trim(mb_substr($title, $pos + 3));
                $title = trim(mb_substr($title, 0, $pos));
            }

            $date = null;
            try {
                $d = $entry->getDateModified() ?: $entry->getDateCreated();
                if ($d) {
                    $date = Carbon::instance($d);
                }
            } catch (\Throwable) {
                $date = null;
            }

            $items[] = [
                'external_id' => $entry->getId() ?: null,
                'title' => $title !== '' ? $title : '(sem título)',
                'url' => $link,
                'description' => $this->clean($entry->getDescription()),
                'published_at' => $date,
                'source_name' => $sourceName,
            ];
        }

        return $items;
    }

    /** Remove HTML/scripts do conteúdo externo (não confiável). */
    private function clean(?string $html): ?string
    {
        if ($html === null) {
            return null;
        }
        $text = trim(html_entity_decode(strip_tags($html)));

        return $text !== '' ? mb_substr($text, 0, 1000) : null;
    }
}
