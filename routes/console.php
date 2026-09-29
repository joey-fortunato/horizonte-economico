<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Publicação automática dos artigos agendados
Schedule::command('articles:publish-scheduled')->everyMinute()->withoutOverlapping();

// Recolha automática de notícias (intervalo configurável em config/news.php)
Schedule::command('news:collect')->cron(config('news.collect_cron'))->withoutOverlapping();

// Purga por retenção (notícias rejeitadas antigas + histórico de recolhas)
Schedule::command('news:purge')->dailyAt('03:30')->withoutOverlapping();

// Processa a fila por cron (estratégia compatível com alojamento sem worker persistente)
Schedule::command('queue:work --stop-when-empty --tries=1 --max-time=55')
    ->everyMinute()
    ->withoutOverlapping();
