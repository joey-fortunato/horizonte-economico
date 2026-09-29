<?php

namespace App\Services\News;

class UrlNormalizer
{
    private const TRACKING = [
        'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content',
        'gclid', 'fbclid', 'mc_cid', 'mc_eid', 'ref', 'ref_src', 'oc',
    ];

    /** Normaliza um URL para deduplicação (host em minúsculas, sem tracking/fragmento/barra final). */
    public function normalize(string $url): string
    {
        $url = trim($url);
        $parts = parse_url($url);
        if ($parts === false || empty($parts['host'])) {
            return rtrim(mb_strtolower($url), '/');
        }

        $scheme = strtolower($parts['scheme'] ?? 'https');
        $host = strtolower($parts['host']);
        $port = isset($parts['port']) ? ':'.$parts['port'] : '';
        $path = $parts['path'] ?? '';
        $path = $path !== '/' ? rtrim($path, '/') : $path;

        $query = '';
        if (! empty($parts['query'])) {
            parse_str($parts['query'], $params);
            foreach (self::TRACKING as $t) {
                unset($params[$t]);
            }
            ksort($params);
            if ($params) {
                $query = '?'.http_build_query($params);
            }
        }

        return "{$scheme}://{$host}{$port}{$path}{$query}";
    }
}
