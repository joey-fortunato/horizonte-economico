<?php

namespace Database\Seeders;

use App\Enums\ArticleStatus;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EditorialSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Categorias a partir da config (fonte de seed; a BD passa a ser a fonte de verdade)
        $position = 0;
        foreach (config('sections') as $slug => $s) {
            Category::updateOrCreate(
                ['slug' => $slug],
                ['name' => $s['label'], 'description' => $s['desc'], 'color' => $s['color'], 'is_active' => true, 'position' => $position++]
            );
        }
        $categories = Category::pluck('id', 'slug');

        // 2) Autores
        $authors = [
            ['name' => 'João Muanza', 'title' => 'Editor de Economia', 'role' => 'administrador', 'bio' => 'Economista e jornalista. Escreve sobre política económica e contas públicas há mais de dez anos.'],
            ['name' => 'Ana Cardoso', 'title' => 'Mercados', 'role' => 'editor', 'bio' => 'Cobre câmbio, dívida soberana e a bolsa angolana.'],
            ['name' => 'Marta Songo', 'title' => 'Finanças pessoais', 'role' => 'author', 'bio' => 'Escreve guias práticos de poupança, orçamento e crédito.'],
            ['name' => 'Pedro Lemos', 'title' => 'Banca & seguros', 'role' => 'editor', 'bio' => 'Analisa produtos financeiros, regulação e o sector segurador.'],
            ['name' => 'Bruno Kiala', 'title' => 'Empresas', 'role' => 'author', 'bio' => 'Acompanha empreendedorismo, investimento e o mercado empresarial.'],
        ];
        $authorIds = [];
        foreach ($authors as $a) {
            $slug = Str::slug($a['name']);
            $user = User::updateOrCreate(
                ['email' => $slug.'@horizonteeconomico.com'],
                [
                    'name' => $a['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'role' => $a['role'],
                    'status' => 'active',
                    'slug' => $slug,
                    'title' => $a['title'],
                    'bio' => $a['bio'],
                ]
            );
            $authorIds[$slug] = $user->id;
        }

        // 3) Tags
        $tagNames = ['OGE 2027', 'Fiscalidade', 'Dívida pública', 'IRT', 'Câmbio', 'Inflação', 'Poupança', 'Crédito', 'Petróleo', 'Investimento'];
        $tagIds = collect($tagNames)->mapWithKeys(fn ($n) => [
            $n => Tag::updateOrCreate(['slug' => Str::slug($n)], ['name' => $n])->id,
        ]);

        // 4) Artigos
        $body = <<<'HTML'
<p>O Orçamento Geral do Estado chega num momento de transição. O Executivo aposta em recuperar investimento público sem perder de vista o controlo da dívida — um equilíbrio difícil que definirá o custo de vida no próximo ano.</p>
<h2>As grandes prioridades</h2>
<p>Educação e saúde continuam a absorver a maior fatia da despesa social, mas é no investimento em infra-estruturas que se nota a maior variação face ao ano anterior.</p>
<blockquote>A verdadeira prova do orçamento não está no que promete, mas no que consegue executar.</blockquote>
<p>O documento prevê ainda uma revisão da tabela do IRT, com potencial alívio para os escalões mais baixos.</p>
HTML;

        $sources = [
            ['title' => 'Proposta de OGE 2027', 'org' => 'Ministério das Finanças', 'url' => '#'],
            ['title' => 'Relatório de Inflação', 'org' => 'Banco Nacional de Angola', 'url' => '#'],
        ];

        $articles = [
            ['OGE 2027: onde vai o dinheiro do Estado e o que muda para as famílias', 'politica-economica', 'joao-muanza', 8, ['OGE 2027', 'Fiscalidade', 'Dívida pública', 'IRT']],
            ['Kwanza recupera face ao dólar após leilão cambial do BNA', 'mercados', 'ana-cardoso', 6, ['Câmbio']],
            ['Novas regras de crédito: o que muda ao pedir financiamento', 'banca-seguros', 'pedro-lemos', 5, ['Crédito']],
            ['Como montar um fundo de emergência com salário em kwanzas', 'financas-pessoais', 'marta-songo', 7, ['Poupança']],
            ['Startups angolanas captam ronda recorde em 2026', 'empresas', 'bruno-kiala', 5, ['Investimento']],
            ['PIB não-petrolífero cresce acima do esperado no 3.º trimestre', 'economia', 'ana-cardoso', 6, ['Inflação']],
            ['Seguro automóvel: guia para escolher a cobertura certa', 'banca-seguros', 'pedro-lemos', 7, ['Crédito']],
            ['Juros compostos explicados com exemplos do dia a dia', 'literacia-financeira', 'marta-songo', 4, ['Poupança']],
            ['Procura por Obrigações do Tesouro sobe no leilão', 'mercados', 'joao-muanza', 5, ['Dívida pública']],
            ['Orçamento familiar: o método 50/30/20 adaptado', 'financas-pessoais', 'marta-songo', 6, ['Poupança']],
            ['Brent acima dos 78 dólares pressiona as receitas', 'mercados', 'joao-muanza', 5, ['Petróleo']],
            ['Abrir empresa em Angola: custos e prazos reais', 'empresas', 'bruno-kiala', 6, ['Investimento']],
        ];

        foreach ($articles as $i => [$title, $catSlug, $authorSlug, $minutes, $tags]) {
            $article = Article::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title,
                    'author_id' => $authorIds[$authorSlug],
                    'category_id' => $categories[$catSlug] ?? null,
                    'excerpt' => 'Uma leitura clara e contextualizada sobre '.Str::lower($title).', com o rigor do Horizonte Económico.',
                    'body' => $body,
                    'status' => ArticleStatus::Published->value,
                    'published_at' => Carbon::now()->subDays($i)->subHours($i),
                    'reading_minutes' => $minutes,
                    'views_count' => random_int(1200, 13000),
                    'seo_title' => $title,
                    'seo_description' => 'Análise do Horizonte Económico: '.$title,
                    'sources' => $i === 0 ? $sources : null,
                    'correction_note' => $i === 0 ? 'Corrigido a 26 Set 2026: a versão inicial indicava 18% em vez de 19,7% para a inflação homóloga.' : null,
                ]
            );
            $article->tags()->sync(collect($tags)->map(fn ($t) => $tagIds[$t])->all());
        }
    }
}
