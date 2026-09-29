<?php

namespace Database\Seeders;

use App\Models\NewsSource;
use Illuminate\Database\Seeder;

class NewsSourceSeeder extends Seeder
{
    public function run(): void
    {
        NewsSource::updateOrCreate(
            ['url' => 'https://news.google.com/rss/search?q=economia+Angola&hl=pt-PT&gl=PT&ceid=PT:pt'],
            [
                'name' => 'Google News — Economia (Angola)',
                'type' => 'google_news_rss',
                'is_active' => true,
            ],
        );

        NewsSource::updateOrCreate(
            ['url' => 'https://news.google.com/rss/search?q=BNA+OR+kwanza+OR+inflação+Angola&hl=pt-PT&gl=PT&ceid=PT:pt'],
            [
                'name' => 'Google News — Mercados & BNA (Angola)',
                'type' => 'google_news_rss',
                'is_active' => true,
            ],
        );
    }
}
