<?php

/*
|--------------------------------------------------------------------------
| Secções editoriais
|--------------------------------------------------------------------------
| Fonte única das categorias do Horizonte Económico. Cada secção tem uma
| cor de código (dessaturada, legível sobre branco — WCAG AA) usada como
| marcador para destacar/distinguir a categoria em cards e páginas.
| Enquanto não existirem categorias na base de dados, isto serve de default.
*/

return [
    'economia' => [
        'label' => 'Economia',
        'color' => '#0b5c47', // verde
        'desc' => 'Indicadores e crescimento',
    ],
    'financas-pessoais' => [
        'label' => 'Finanças pessoais',
        'color' => '#0e6f74', // teal
        'desc' => 'Poupança e crédito',
    ],
    'banca-seguros' => [
        'label' => 'Banca & seguros',
        'color' => '#3f4ea3', // índigo
        'desc' => 'Produtos e regulação',
    ],
    'empresas' => [
        'label' => 'Empresas',
        'color' => '#8a5e12', // bronze
        'desc' => 'Negócios e investimento',
    ],
    'mercados' => [
        'label' => 'Mercados',
        'color' => '#2f5fa6', // azul
        'desc' => 'Câmbio e matérias-primas',
    ],
    'politica-economica' => [
        'label' => 'Política económica',
        'color' => '#9e3b3b', // bordô
        'desc' => 'OGE e fiscalidade',
    ],
    'literacia-financeira' => [
        'label' => 'Literacia financeira',
        'color' => '#6a4a8f', // violeta
        'desc' => 'Guias e explicações',
    ],
];
