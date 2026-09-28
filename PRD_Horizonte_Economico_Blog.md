# PRD --- Horizonte Económico

**Product Requirements Document**\
**Versão:** 1.0\
**Data:** Setembro de 2026\
**Produto:** Blog e plataforma editorial\
**Domínio previsto:** `horizonteeconomico.com`\
**Tecnologia prevista:** Laravel

------------------------------------------------------------------------

## 1. Resumo executivo

O Horizonte Económico será uma plataforma digital de informação, análise
e literacia económica, inicialmente focada em Angola.

A primeira fase será dedicada à componente editorial: um blog
profissional que permita publicar notícias, artigos de análise,
explicações económicas e conteúdos educativos.

O objectivo não é criar apenas mais um portal de notícias. A plataforma
deverá ajudar os leitores a compreender os acontecimentos económicos, as
suas causas e as consequências para a vida das pessoas e das empresas.

O sistema terá duas componentes principais:

-   **Frontend público:** experiência de leitura rápida, intuitiva,
    responsiva e optimizada para motores de busca.
-   **Backoffice editorial:** área privada para gerir artigos,
    categorias, autores, imagens, conteúdos e publicações.

### Objectivo do MVP

Lançar uma plataforma editorial funcional, segura e escalável, que
permita à equipa publicar conteúdos de forma autónoma e aos leitores
encontrar, consumir e partilhar informação económica.

## 2. Visão e posicionamento

**Missão:** tornar a economia mais compreensível e acessível, promovendo
decisões financeiras mais informadas.

**Público-alvo inicial:** - Jovens profissionais e estudantes
universitários. - Empreendedores e gestores de empresas. - Trabalhadores
interessados em finanças pessoais. - Leitores interessados na economia
angolana. - Profissionais de banca, seguros, investimento e negócios.

**Proposta de valor:**\
\> Compreender a economia para tomar melhores decisões.

O conteúdo deverá privilegiar a contextualização, a clareza e a
credibilidade. Os artigos devem distinguir factos, análise e opinião,
apresentar fontes sempre que aplicável e indicar a data de publicação e
de actualização.

## 3. Objectivos do produto

### 3.1 Objectivos principais

1.  Disponibilizar um portal editorial profissional e acessível em
    dispositivos móveis.
2.  Permitir a publicação e gestão de conteúdos sem intervenção de um
    programador.
3.  Organizar os artigos por categorias e temas económicos.
4.  Melhorar a descoberta de conteúdos através de pesquisa, navegação e
    SEO.
5.  Construir uma audiência recorrente e uma base de leitores.
6.  Criar uma arquitectura que permita adicionar ferramentas financeiras
    e outros produtos futuramente.

### 3.2 Indicadores de sucesso

  Indicador               Objectivo inicial
  ----------------------- ----------------------------------------------------
  Publicação de artigos   Processo integralmente gerido no backoffice
  Responsividade          Suporte a telemóveis, tablets e computadores
  SEO                     Metadados e URLs optimizados
  Desempenho              Carregamento rápido, especialmente em redes móveis
  Gestão editorial        Criar, editar, agendar e publicar artigos
  Segurança               Acesso protegido ao painel editorial
  Audiência               Medir visitas, leituras e artigos mais consultados

As metas quantitativas de audiência e desempenho deverão ser definidas
após os primeiros testes e a recolha de dados reais.

## 4. Âmbito do MVP

### 4.1 Incluído no MVP

-   Homepage editorial com artigos em destaque e recentes.
-   Páginas de artigos com conteúdo multimédia.
-   Categorias e páginas de arquivo.
-   Pesquisa de artigos.
-   Páginas de autor e apresentação da publicação.
-   Backoffice para gestão de artigos e categorias.
-   Gestão de imagens e metadados SEO.
-   Agendamento de publicações.
-   Partilha de artigos nas redes sociais.
-   Integração com ferramentas de análise de tráfego.

### 4.2 Fora do MVP

-   Simulador de crédito e outras calculadoras financeiras.
-   Aplicação móvel nativa.
-   Comentários públicos e fóruns.
-   Subscrições pagas e paywall.
-   Personalização avançada do feed.
-   Marketplace de produtos financeiros.
-   Publicação automática de notícias por inteligência artificial.

## 5. Arquitectura da informação

A plataforma deverá ter uma estrutura simples, com navegação intuitiva e
espaço para expansão.

  -----------------------------------------------------------------------
  Página                  URL proposta            Descrição
  ----------------------- ----------------------- -----------------------
  Homepage                `/`                     Destaques, artigos
                                                  recentes e categorias

  Artigo                  `/artigo/{slug}`        Conteúdo integral de um
                                                  artigo

  Categoria               `/categoria/{slug}`     Artigos de uma
                                                  categoria

  Pesquisa                `/pesquisa?q={termo}`   Resultados de pesquisa

  Autor                   `/autor/{slug}`         Biografia e artigos do
                                                  autor

  Sobre                   `/sobre`                Apresentação do
                                                  Horizonte Económico

  Contactos               `/contactos`            Formulário e contactos
                                                  editoriais

  Política editorial      `/politica-editorial`   Princípios de
                                                  publicação e correcções

  Privacidade             `/privacidade`          Política de privacidade

  Termos                  `/termos`               Condições de utilização
  -----------------------------------------------------------------------

As URLs deverão ser legíveis, permanentes e independentes dos
identificadores internos da base de dados.

### 5.1 Categorias editoriais

Categorias iniciais propostas:

-   **Economia:** indicadores, crescimento, inflação e actividade
    económica.
-   **Finanças pessoais:** poupança, orçamento, crédito e gestão do
    dinheiro.
-   **Banca e seguros:** produtos, serviços, instituições e regulação.
-   **Empresas e negócios:** empreendedorismo, investimento e mercado
    empresarial.
-   **Mercados:** câmbio, matérias-primas e mercados financeiros.
-   **Política económica:** orçamento de Estado, fiscalidade e decisões
    económicas.
-   **Literacia financeira:** artigos explicativos e guias educativos.

As categorias deverão ser configuráveis pelo administrador. Um artigo
poderá pertencer a uma categoria principal e ter etiquetas adicionais.

## 6. Requisitos funcionais

### 6.1 Homepage

**Prioridade:** P0 --- Obrigatório.

A homepage será a principal porta de entrada para os leitores.

Componentes: 1. **Cabeçalho:** logótipo, menu principal, pesquisa e menu
móvel. 2. **Artigo principal:** imagem de destaque, categoria, título,
resumo e ligação para o artigo. 3. **Últimas publicações:** lista
cronológica dos artigos publicados. 4. **Mais lidos:** artigos com maior
número de visualizações, numa janela temporal configurável. 5.
**Explorar categorias:** acesso rápido aos principais temas. 6.
**Newsletter:** formulário opcional para subscrição por e-mail. 7.
**Rodapé:** páginas institucionais, redes sociais e contactos.

O administrador deverá poder definir o artigo em destaque e,
futuramente, seleccionar manualmente a ordem dos conteúdos apresentados.

### 6.2 Página de artigo

**Prioridade:** P0 --- Obrigatório.

Cada artigo deverá apresentar: - Título. - Subtítulo ou resumo. - Imagem
de capa. - Categoria principal e etiquetas. - Autor. - Data de
publicação e, quando aplicável, data de actualização. - Tempo estimado
de leitura. - Corpo do artigo com formatação. - Imagens e legendas. -
Referências e fontes. - Botões de partilha. - Artigos relacionados.

O editor deverá permitir títulos intermédios, parágrafos, listas,
citações, hiperligações, imagens e tabelas simples.

**Requisito editorial:** o artigo deve permitir identificar claramente o
autor, as fontes utilizadas e as alterações posteriores. Um conteúdo
corrigido deverá poder apresentar uma nota de correcção, sem apagar o
histórico editorial.

### 6.3 Categorias e etiquetas

**Prioridade:** P0 --- Obrigatório.

O sistema deverá permitir: - Criar, editar e desactivar categorias. -
Definir nome, descrição e slug. - Associar artigos a categorias. -
Associar várias etiquetas a um artigo. - Filtrar artigos por categoria e
etiqueta. - Apresentar paginação nos arquivos.

Categorias desactivadas não deverão eliminar os artigos associados. A
gestão de categorias deverá respeitar as URLs já indexadas, evitando
erros 404 desnecessários.

### 6.4 Pesquisa

**Prioridade:** P0 --- Obrigatório.

O leitor poderá pesquisar artigos através de um campo de pesquisa
acessível no cabeçalho.

A pesquisa deverá considerar: - Título. - Resumo. - Corpo do artigo. -
Categorias e etiquetas.

Os resultados deverão apresentar título, imagem, categoria, data e
resumo.

A pesquisa deverá suportar acentos e diferenças entre maiúsculas e
minúsculas. Quando não existirem resultados, deverá apresentar uma
mensagem clara e sugerir a exploração de categorias.

### 6.5 Autores

**Prioridade:** P0 --- Obrigatório.

Cada autor deverá ter um perfil com: - Nome. - Fotografia. -
Biografia. - Cargo ou função editorial. - Ligações para redes sociais,
quando aplicável. - Lista dos artigos publicados.

Um artigo deverá ter pelo menos um autor responsável. O sistema poderá
permitir vários autores numa fase posterior.

### 6.6 Newsletter

**Prioridade:** P1 --- Importante.

A newsletter deverá permitir que os leitores subscrevam uma lista de
distribuição.

Requisitos: - Campo de e-mail. - Validação do endereço. - Confirmação de
subscrição. - Registo da data e origem da inscrição. - Possibilidade de
cancelamento da subscrição. - Consentimento explícito para receber
comunicações.

A primeira versão poderá utilizar um serviço externo de envio de
e-mails. O sistema deverá manter a possibilidade de integrar outros
fornecedores sem alterar a experiência do leitor.

### 6.7 Partilha nas redes sociais

**Prioridade:** P0 --- Obrigatório.

Cada artigo deverá disponibilizar opções de partilha para plataformas
como WhatsApp, Facebook, LinkedIn e X.

A partilha deverá utilizar o URL canónico e o título do artigo. O site
deverá disponibilizar metadados Open Graph para apresentar correctamente
a imagem, o título e a descrição nas redes sociais.

## 7. Backoffice editorial

**Prioridade:** P0 --- Obrigatório.

O backoffice é uma das componentes mais importantes do MVP. Deverá
permitir que o projecto seja gerido sem depender de alterações ao
código.

### 7.1 Dashboard

O painel inicial deverá apresentar: - Total de artigos publicados. -
Total de artigos em rascunho. - Artigos agendados. - Artigos
recentemente actualizados. - Visualizações, caso a integração analítica
esteja disponível. - Atalhos para criar artigos e gerir categorias.

Os dados de audiência deverão ser apresentados apenas quando existir uma
fonte de medição configurada.

### 7.2 Gestão de artigos

O administrador e os editores autorizados deverão poder criar, editar,
pré-visualizar, publicar, agendar e arquivar artigos.

  Campo                         Obrigatório
  ----------------------------- -----------------------------
  Título                        Sim
  Slug                          Sim, gerado automaticamente
  Resumo                        Sim
  Corpo do artigo               Sim
  Imagem de capa                Sim
  Texto alternativo da imagem   Sim
  Categoria principal           Sim
  Etiquetas                     Não
  Autor                         Sim
  Estado editorial              Sim
  Data de publicação            Conforme o estado
  Título SEO                    Não
  Descrição SEO                 Não
  Imagem para partilha          Não
  Fontes e referências          Recomendado

#### Estados de um artigo

  Estado       Comportamento
  ------------ -------------------------------------------
  Rascunho     Visível apenas a utilizadores autorizados
  Em revisão   Aguarda aprovação editorial
  Agendado     Publicação automática na data definida
  Publicado    Visível ao público
  Arquivado    Deixa de aparecer nas listagens públicas

A publicação agendada deverá ser processada por uma tarefa programada do
Laravel. Se a tarefa falhar, o artigo deverá permanecer registado como
agendado até ser publicado com sucesso.

### 7.3 Editor de conteúdo

O editor deverá oferecer uma experiência semelhante à de um editor de
texto moderno.

Funcionalidades necessárias: - Formatação de títulos e parágrafos. -
Negrito e itálico. - Listas ordenadas e não ordenadas. - Citações. -
Hiperligações. - Inserção e alinhamento de imagens. - Legendas de
imagens. - Tabelas simples. - Pré-visualização do artigo.

O conteúdo deverá ser armazenado num formato estruturado, de forma a
permitir a apresentação consistente no frontend e facilitar futuras
migrações.

### 7.4 Gestão de utilizadores e permissões

O MVP deverá suportar, no mínimo, três perfis:

  -----------------------------------------------------------------------
  Perfil                              Permissões
  ----------------------------------- -----------------------------------
  Administrador                       Acesso completo ao backoffice e
                                      gestão de utilizadores

  Editor                              Criar, editar, rever e publicar
                                      artigos

  Autor                               Criar e editar os próprios artigos,
                                      sem publicar directamente
  -----------------------------------------------------------------------

O sistema deverá aplicar autorização no servidor, não apenas esconder
botões na interface.

### 7.5 Biblioteca multimédia

A biblioteca deverá permitir: - Carregar imagens. - Definir texto
alternativo. - Visualizar imagens carregadas. - Associar imagens aos
artigos. - Eliminar imagens que não estejam em utilização. - Gerar
versões optimizadas para diferentes dimensões.

As imagens deverão ser validadas por tipo e tamanho. A eliminação de
ficheiros associados a artigos publicados deverá ser impedida ou sujeita
a confirmação e verificação de dependências.

## 8. Requisitos não funcionais

  -----------------------------------------------------------------------
  Área                                Requisito
  ----------------------------------- -----------------------------------
  Desempenho                          Optimizar imagens, consultas e
                                      recursos estáticos

  Responsividade                      Compatibilidade com telemóveis,
                                      tablets e computadores

  Segurança                           Protecção contra CSRF, XSS, SQL
                                      injection e acessos não autorizados

  SEO                                 URLs canónicos, sitemap XML,
                                      robots.txt e metadados

  Acessibilidade                      Navegação por teclado, contraste
                                      adequado e textos alternativos

  Disponibilidade                     Monitorização de erros e cópias de
                                      segurança regulares

  Compatibilidade                     Suporte às versões actuais dos
                                      principais navegadores

  Manutenção                          Código modular, testável e
                                      documentado

  Internacionalização                 Preparação para português e futuras
                                      traduções

  Privacidade                         Recolha mínima de dados e gestão de
                                      consentimentos
  -----------------------------------------------------------------------

### 8.1 Desempenho

A experiência deverá ser optimizada para leitores que utilizam redes
móveis com velocidades variáveis.

Critérios propostos: - Utilizar formatos modernos, como WebP ou AVIF,
quando suportados. - Aplicar carregamento diferido às imagens abaixo da
área inicialmente visível. - Evitar JavaScript desnecessário. - Utilizar
paginação em vez de carregar todos os artigos. - Utilizar cache para
páginas e consultas adequadas, use o skeleton para melhorar a experiência do utilizador nesse quesito.

Metas de desempenho propostas, medidas no percentil 75:

  Métrica         Meta
  --------- ----------
  LCP          ≤ 2,5 s
  INP         ≤ 200 ms
  CLS            ≤ 0,1

Estas metas devem ser avaliadas em condições reais, não apenas em
ambiente local.

### 8.2 SEO

O sistema deverá gerar automaticamente: - URLs amigáveis. - Títulos e
descrições para motores de busca. - Tags Open Graph. - Sitemap XML. -
URLs canónicos. - Dados estruturados `Article` ou `NewsArticle`,
conforme o tipo de conteúdo. - Breadcrumbs, quando aplicável.

Os artigos publicados deverão poder ser indexados. Rascunhos,
pré-visualizações e páginas privadas não deverão ser indexáveis.

O backoffice deverá permitir personalizar os principais metadados sem
editar código.

## 9. Arquitectura técnica proposta

Considerando a utilização de Laravel no desenvolvimento, propõe-se uma
arquitectura monolítica modular para o MVP. Não há necessidade de
introduzir microserviços nesta fase.

### 9.1 Arquitectura lógica

-   **Frontend público:** homepage, artigos, categorias e pesquisa.
-   **Aplicação Laravel:** rotas, controllers, services, policies e
    jobs.
-   **Base de dados:** Postgres.
-   **Armazenamento:** imagens e ficheiros através do Laravel Storage.
-   **Scheduler:** processamento de publicações agendadas.
-   **Analytics:** medição de audiência.

### 9.2 Stack tecnológica recomendada

  Componente        Tecnologia proposta
  ----------------- ----------------------------------------------------
  Backend           Laravel
  Frontend          Blade e Tailwind CSS
  Interactividade   Alpine.js, quando necessário
  Base de dados     Postgres
  Autenticação      Laravel Breeze ou solução equivalente
  Editor            Tiptap ou editor compatível
  Imagens           Laravel Storage
  Cache             Cache de ficheiros ou Redis, conforme o alojamento
  Testes            PHPUnit ou Pest
  Analytics         Google Analytics 4 ou alternativa compatível

A utilização de Blade permite desenvolver um portal rápido, com
renderização no servidor e boa compatibilidade com SEO. Não é necessário
recorrer a uma SPA para este tipo de produto.

A escolha definitiva do editor e das dependências deverá considerar a
versão de Laravel e o ambiente de alojamento.

## 10. Modelo de dados

O modelo inicial deverá incluir as seguintes entidades:

  -----------------------------------------------------------------------
  Entidade                            Campos principais
  ----------------------------------- -----------------------------------
  `users`                             id, name, email, password, role,
                                      status

  `articles`                          id, author_id, title, slug,
                                      excerpt, body, cover_image, status,
                                      published_at, updated_at

  `categories`                        id, name, slug, description

  `tags`                              id, name, slug

  `article_category`                  Relação entre artigos e categorias

  `article_tag`                       Relação entre artigos e etiquetas

  `media`                             id, path, alt_text, mime_type, size

  `article_views`                     id, article_id, viewed_at

  `newsletter_subscribers`            id, email, status, consented_at,
                                      unsubscribed_at
  -----------------------------------------------------------------------

O sistema deverá ainda manter registos de auditoria para operações
editoriais importantes, como publicação, alteração de estado e
eliminação de artigos.

Este esquema é conceptual. As relações e os campos finais deverão ser
definidos durante a implementação.

## 11. Fluxos principais do utilizador

### 11.1 Leitor

1.  O leitor acede à homepage.
2.  Explora um artigo em destaque, uma categoria ou a pesquisa.
3.  Abre o artigo e consulta o conteúdo.
4.  Partilha o artigo ou acede a conteúdos relacionados.
5.  Opcionalmente, subscreve a newsletter.

### 11.2 Autor

1.  Inicia sessão no backoffice.
2.  Cria um artigo e preenche os campos editoriais.
3.  Guarda o artigo como rascunho ou envia para revisão.
4.  O editor revê e aprova o conteúdo.
5.  O artigo é publicado imediatamente ou agendado.

## 12. Critérios de aceitação

O MVP só deverá ser considerado funcional quando os seguintes cenários
forem testados com sucesso.

  -----------------------------------------------------------------------
  ID                      Critério de aceitação   Prioridade
  ----------------------- ----------------------- -----------------------
  AC-01                   Um visitante consegue   P0
                          consultar a homepage    
                          sem autenticação.       

  AC-02                   Um visitante consegue   P0
                          abrir e ler um artigo   
                          publicado.              

  AC-03                   Um visitante consegue   P0
                          navegar pelas           
                          categorias.             

  AC-04                   A pesquisa devolve      P0
                          resultados relevantes.  

  AC-05                   Um autor consegue criar P0
                          e guardar um rascunho.  

  AC-06                   Um editor consegue      P0
                          rever e publicar um     
                          artigo.                 

  AC-07                   Um artigo agendado é    P0
                          publicado na data       
                          definida.               

  AC-08                   Um utilizador não       P0
                          autorizado não consegue 
                          aceder ao backoffice.   

  AC-09                   As páginas de artigos   P0
                          apresentam metadados    
                          SEO.                    

  AC-10                   O leitor consegue       P0
                          partilhar um artigo.    

  AC-11                   O leitor consegue       P1
                          subscrever a            
                          newsletter.             

  AC-12                   O administrador         P1
                          consegue consultar      
                          métricas de audiência.  
  -----------------------------------------------------------------------

Cada funcionalidade deverá ter testes automatizados para os
comportamentos críticos e testes manuais de interface nos principais
tamanhos de ecrã.

## 13. Segurança e conformidade

O sistema deverá cumprir os seguintes requisitos: - Autenticação segura
e protecção contra tentativas repetidas de acesso. - Políticas de
autorização para cada perfil editorial. - Validação de todos os dados
recebidos. - Sanitização do conteúdo HTML para prevenir XSS. - Protecção
contra CSRF e SQL injection. - Registo das operações administrativas
relevantes. - Cópias de segurança da base de dados e dos ficheiros
multimédia. - Política de privacidade e mecanismo de gestão de
consentimento para a newsletter e ferramentas analíticas, conforme a
legislação aplicável.

As credenciais e chaves de serviços externos deverão ser guardadas em
variáveis de ambiente, nunca no código-fonte.

## 14. Fases de desenvolvimento

Estimativa inicial para um programador com experiência em Laravel. Os
prazos deverão ser ajustados após a definição do design e do alojamento.

  ------------------------------------------------------------------------
  Fase                  Actividades                             Estimativa
  --------------------- --------------------- ----------------------------
  1\. Planeamento e     Identidade visual,                       3--5 dias
  design                wireframes e          
                        arquitectura da       
                        informação            

  2\. Estrutura técnica Configuração Laravel,                    3--5 dias
                        modelos, migrations,  
                        autenticação e        
                        permissões            

  3\. Backoffice        Dashboard, editor,                      7--10 dias
  editorial             gestão de artigos,    
                        categorias, media e   
                        agendamento           

  4\. Frontend público  Homepage, artigos,                      7--10 dias
                        categorias, pesquisa, 
                        SEO e responsividade  

  5\. Testes e          Testes, optimização,                     4--7 dias
  lançamento            analytics, backups e  
                        publicação            
  ------------------------------------------------------------------------

**Estimativa total: 24--37 dias úteis.**

Esta é uma estimativa de execução, não um compromisso de calendário. Não
inclui atrasos de aprovação editorial, produção de conteúdos ou
dependências externas.

## 15. Riscos e medidas de mitigação

  -----------------------------------------------------------------------
  Risco                   Impacto                 Mitigação
  ----------------------- ----------------------- -----------------------
  Baixa frequência de     Redução da audiência    Calendário editorial e
  publicação              recorrente              planeamento antecipado

  Conteúdos com erros     Perda de credibilidade  Revisão editorial e
  factuais                                        fontes verificáveis

  Lentidão em redes       Abandono dos leitores   Optimização de imagens
  móveis                                          e cache

  Problemas de segurança  Comprometimento do      Actualizações, testes e
                          portal                  backups

  Dependência de um único Atrasos nas publicações Permissões e fluxo de
  editor                                          revisão

  Custos de serviços      Aumento dos custos      Começar com serviços
  externos                operacionais            essenciais e económicos

  Dificuldade em atrair   Baixo crescimento       SEO, distribuição e
  audiência                                       análise de
                                                  comportamento
  -----------------------------------------------------------------------

## 16. Funcionalidades futuras

Depois do lançamento e da validação da audiência, poderão ser
desenvolvidas novas funcionalidades:

### Horizonte Tools

Simuladores de crédito, inflação, poupança e juros compostos.

### Horizonte Data

Indicadores económicos, séries históricas, gráficos e dashboards.

### Horizonte Academy

Cursos e conteúdos estruturados de literacia financeira e económica.

### Horizonte Pro

Relatórios, análises e ferramentas para empresas e profissionais.

A arquitectura do MVP deverá permitir estas extensões sem exigir a
reconstrução do portal editorial.

## 17. Definição de pronto (Definition of Done)

Uma funcionalidade será considerada concluída quando: - Cumprir os
requisitos funcionais definidos. - Estiver integrada no sistema de
permissões. - Tiver validação dos dados e tratamento de erros. - Passar
os testes automatizados relevantes. - Estiver funcional em dispositivos
móveis e computadores. - Não introduzir erros críticos de segurança ou
desempenho. - Tiver documentação técnica suficiente para manutenção. -
Estiver disponível no ambiente de produção, quando aplicável.

## 18. Decisões pendentes

Antes de iniciar a implementação, é necessário fechar as seguintes
decisões:

-   Identidade visual e logótipo definitivos.
-   Editor de conteúdo a utilizar.
-   Fornecedor de newsletter.
-   Ferramenta de analytics.
-   Ambiente de alojamento e configuração do cron.
-   Fluxo de aprovação editorial.
-   Política de publicação e correcção de artigos.

## 19. Conclusão

O MVP do Horizonte Económico deverá concentrar-se em três pilares: **uma
boa experiência de leitura, um backoffice editorial eficiente e uma base
técnica preparada para crescer**.

A prioridade não é construir imediatamente uma plataforma complexa. É
lançar um portal que permita publicar conteúdo rigoroso com
regularidade, compreender o comportamento dos leitores e validar o
interesse do mercado.

A implementação deverá começar pela arquitectura e pelo fluxo editorial,
seguindo-se o frontend público e, finalmente, os testes e a publicação.

**Resultado esperado:** um portal editorial funcional, administrável
pela equipa editorial, optimizado para dispositivos móveis e preparado
para receber as futuras ferramentas e serviços do Horizonte Económico.
