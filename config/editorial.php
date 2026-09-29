<?php

/*
|--------------------------------------------------------------------------
| Linha editorial — sinais de relevância
|--------------------------------------------------------------------------
| Palavras/expressões que reflectem o foco do Horizonte Económico (economia
| de Angola). Cada uma tem um peso. A pontuação de uma notícia é a soma dos
| pesos dos termos encontrados no título/descrição (sem acentos, por palavra)
| mais um bónus de actualidade. Notícias acima do limiar são "melhores".
| A correspondência é insensível a acentos e maiúsculas.
*/

return [
    'keywords' => [
        // núcleo macro
        'inflação' => 5,
        'kwanza' => 5,
        'bna' => 5,
        'banco nacional de angola' => 5,
        'oge' => 5,
        'orçamento geral do estado' => 5,
        'dívida' => 4,
        'pib' => 4,
        'taxa de juro' => 4,
        'taxa diretora' => 4,
        'câmbio' => 4,
        'reservas' => 3,
        // sectores
        'banca' => 3,
        'crédito' => 3,
        'seguros' => 2,
        'bolsa' => 3,
        'bodiva' => 4,
        'obrigações do tesouro' => 4,
        'petróleo' => 3,
        'brent' => 3,
        'diamantes' => 2,
        'exportações' => 3,
        'fiscalidade' => 3,
        'irt' => 3,
        'imposto' => 2,
        'investimento' => 3,
        'fmi' => 3,
        'moody' => 3,
        // âmbito geográfico (reforça foco Angola)
        'angola' => 3,
        'luanda' => 1,
    ],

    // Termos que penalizam (fora da linha editorial económica)
    'negative' => [
        'futebol' => 4,
        'celebridade' => 4,
        'horóscopo' => 5,
    ],

    'highlight_threshold' => 8, // pontuação mínima para "melhores"
    'recency_days' => 3,        // publicado nos últimos N dias
    'recency_bonus' => 3,
];
