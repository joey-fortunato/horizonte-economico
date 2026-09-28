<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [];

        $add = function (string $loc, ?string $lastmod = null, string $freq = 'weekly', string $priority = '0.6') use (&$urls) {
            $urls[] = compact('loc', 'lastmod', 'freq', 'priority');
        };

        $add(url('/'), now()->toAtomString(), 'daily', '1.0');
        foreach (['sobre', 'contactos', 'politica-editorial', 'privacidade', 'termos'] as $p) {
            $add(url($p), null, 'monthly', '0.3');
        }
        foreach (Category::active()->get() as $c) {
            $add(url('categoria/'.$c->slug), $c->updated_at?->toAtomString(), 'daily', '0.7');
        }
        foreach (User::whereNotNull('slug')->get() as $u) {
            $add(url('autor/'.$u->slug), null, 'weekly', '0.4');
        }
        Article::published()->select('slug', 'updated_at')->orderByDesc('updated_at')
            ->chunk(500, function ($chunk) use ($add) {
                foreach ($chunk as $a) {
                    $add(url('artigo/'.$a->slug), $a->updated_at?->toAtomString(), 'weekly', '0.8');
                }
            });

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        foreach ($urls as $u) {
            $xml .= '  <url><loc>'.e($u['loc']).'</loc>';
            if ($u['lastmod']) {
                $xml .= '<lastmod>'.$u['lastmod'].'</lastmod>';
            }
            $xml .= '<changefreq>'.$u['freq'].'</changefreq><priority>'.$u['priority'].'</priority></url>'."\n";
        }
        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    public function robots(): Response
    {
        $body = "User-agent: *\n";
        $body .= "Allow: /\n";
        $body .= "Disallow: /dashboard\n";
        $body .= "Disallow: /settings\n";
        $body .= "Disallow: /pesquisa\n";
        $body .= "\nSitemap: ".url('sitemap.xml')."\n";

        return response($body, 200, ['Content-Type' => 'text/plain']);
    }
}
