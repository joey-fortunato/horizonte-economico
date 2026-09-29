<?php

return [
    // Expressão cron da recolha automática (por defeito: de hora a hora).
    'collect_cron' => env('NEWS_COLLECT_CRON', '0 * * * *'),

    // Retenção (dias) para purga automática diária.
    'retention_rejected_days' => (int) env('NEWS_RETENTION_REJECTED_DAYS', 90),
    'retention_runs_days' => (int) env('NEWS_RETENTION_RUNS_DAYS', 30),
];
