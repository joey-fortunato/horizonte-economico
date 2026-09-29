<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Publicação automática dos artigos agendados
Schedule::command('articles:publish-scheduled')->everyMinute()->withoutOverlapping();

// Recolha de notícias das fontes activas (RSS / Google News RSS)
Schedule::command('news:collect')->hourly()->withoutOverlapping();
