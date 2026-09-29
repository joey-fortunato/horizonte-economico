# PRD --- Central de Notícias

**Produto:** Horizonte Económico\
**Módulo:** Central de Notícias\
**Versão:** 1.0\
**Estado:** Proposta para implementação\
**Data:** Setembro de 2026

---

## 1. Resumo executivo

A Central de Notícias é um módulo interno do Horizonte Económico
destinado a recolher, organizar, analisar e seleccionar notícias
provenientes de fontes externas, permitindo à equipa editorial
transformar conteúdos relevantes em artigos próprios no blog Laravel.

O módulo deverá reduzir o trabalho manual de descoberta de notícias,
centralizar a triagem editorial e manter um fluxo controlado entre a
recolha de conteúdos e a publicação. A recolha automatizada não
substitui a decisão editorial: cada notícia deverá ser analisada por um
membro autorizado antes de ser convertida num artigo.

A primeira versão deverá privilegiar uma implementação nativa no
Laravel, com recolha através de RSS e Google News RSS, gestão de fontes,
deduplicação, filtros, notas editoriais e criação de rascunhos.

## 2. Contexto e problema

A equipa editorial precisa de acompanhar diversas fontes para
identificar acontecimentos económicos relevantes, sobretudo em Angola.
Quando a descoberta e a organização são feitas manualmente, podem surgir
dificuldades como: - dispersão de notícias por vários sites e canais; -
repetição de conteúdos ou registos da mesma notícia; - dificuldade em
acompanhar o que já foi analisado; - perda de contexto e de referências
da fonte original; - ausência de um fluxo uniforme entre descoberta,
selecção, redacção e publicação.

A Central de Notícias deverá reunir estas actividades numa interface
interna e permitir que a equipa mantenha o controlo editorial.

## 3. Visão do produto

Disponibilizar uma área administrativa onde os utilizadores autorizados
possam gerir fontes, recolher notícias, analisar os resultados, registar
decisões editoriais e iniciar a criação de artigos para o blog.

### 3.1 Objectivos

1.  Centralizar a recolha de notícias externas.
2.  Facilitar a descoberta de conteúdos relevantes para a linha
    editorial.
3.  Reduzir duplicados e trabalho repetitivo.
4.  Permitir a triagem e o acompanhamento do estado de cada notícia.
5.  Preservar a ligação à fonte original.
6.  Facilitar a conversão de uma notícia seleccionada num rascunho de
    artigo.
7.  Manter um histórico mínimo das operações de recolha e das decisões
    editoriais.

### 3.2 Fora dos objectivos do MVP

- Publicação automática de notícias externas.
- Reprodução integral de artigos de terceiros.
- Geração automática de artigos por inteligência artificial.
- Sistema avançado de análise de sentimento ou classificação
  semântica.
- Integrações pagas com fornecedores de notícias, salvo decisão
  posterior.
- Substituição do CMS ou do fluxo editorial já existente.

## 4. Utilizadores e permissões

### 4.1 Administrador

Pode gerir fontes, configurar e executar recolhas, consultar resultados
e gerir o acesso ao módulo, de acordo com as permissões existentes na
aplicação.

### 4.2 Editor

Pode consultar notícias recolhidas, tomar decisões editoriais, adicionar
notas, seleccionar conteúdos e encaminhá-los para redacção ou revisão.

### 4.3 Redactor

Pode consultar notícias seleccionadas e utilizar as respectivas
referências para preparar artigos, conforme as permissões configuradas.

### 4.4 Regras de autorização

- O acesso ao módulo deve ser protegido pelo sistema de autenticação
  existente.
- As acções de gestão de fontes e de execução de recolhas devem exigir
  permissões apropriadas.
- As acções editoriais devem respeitar os papéis e permissões da
  aplicação.
- A autorização deve ser aplicada no servidor, não apenas ocultando
  elementos da interface.

## 5. Âmbito funcional

### 5.1 MVP

- gestão de fontes de notícias;
- recolha manual e agendada;
- suporte inicial a RSS e Google News RSS;
- listagem centralizada de notícias recolhidas;
- pesquisa e filtros;
- deduplicação;
- página de detalhe da notícia;
- selecção, rejeição e notas editoriais;
- criação de rascunho de artigo a partir de uma notícia seleccionada;
- registo de execuções e erros de recolha.

### 5.2 Evolução futura

- novas integrações e APIs;
- classificação automática por tema;
- apoio de IA à síntese ou preparação de rascunhos;
- regras avançadas de relevância;
- alertas e notificações;
- métricas de desempenho editorial;
- detecção de notícias relacionadas entre diferentes fontes.

## 6. Requisitos funcionais

### RF-001 --- Gestão de fontes

O sistema deve permitir aos utilizadores autorizados criar, editar,
activar e desactivar fontes, indicando nome, URL, tipo e estado. Deve
guardar os parâmetros necessários à recolha e permitir consultar a
última recolha e o respectivo resultado. Uma fonte desactivada não
deverá ser incluída nas recolhas agendadas.

### RF-002 --- Recolha de notícias

O sistema deve permitir iniciar uma recolha manual e executar recolhas
agendadas. A recolha deverá consultar apenas fontes activas, obter os
itens disponibilizados, normalizar os dados relevantes, registar a
execução e tratar falhas de uma fonte sem impedir, sempre que possível,
o processamento das restantes.

### RF-003 --- Central de notícias

O sistema deve apresentar uma lista de notícias recolhidas com título,
fonte, data de publicação (quando disponível), data de recolha, estado
editorial e ligação para a notícia original. A lista deve permitir abrir
o detalhe de cada registo.

### RF-004 --- Pesquisa e filtros

O utilizador deve poder pesquisar notícias por texto e filtrar, no
mínimo, por estado editorial, fonte, intervalo de datas e notícias ainda
não analisadas. Os filtros devem poder ser combinados.

### RF-005 --- Deduplicação

O sistema deve evitar a criação de registos duplicados quando a mesma
notícia for recolhida mais do que uma vez. A deduplicação deverá
considerar, conforme os dados disponíveis, o identificador do item RSS,
o URL normalizado e/ou uma combinação de título e fonte. A estratégia
exacta deverá reduzir falsos positivos e preservar notícias distintas.

### RF-006 --- Detalhe da notícia

A página de detalhe deve apresentar os metadados disponíveis, incluindo
título, descrição ou excerto, fonte, URL original e datas relevantes. O
sistema deve manter a referência à fonte original. O conteúdo integral
de terceiros não deve ser copiado ou republicado automaticamente.

### RF-007 --- Decisão editorial

Os utilizadores com permissão devem poder seleccionar ou rejeitar uma
notícia, registar uma nota editorial e consultar o estado actual e o
histórico disponível. Uma notícia rejeitada não deve ser convertida em
artigo sem uma alteração explícita do seu estado ou uma acção autorizada
equivalente.

### RF-008 --- Conversão em artigo

O sistema deve permitir iniciar um artigo no CMS a partir de uma notícia
seleccionada. A operação deverá, sempre que compatível com o modelo
existente, criar um artigo em estado de rascunho, preencher o título
inicial com o título da notícia, preservar a URL e a fonte de origem,
associar o artigo à notícia recolhida e permitir que o redactor
reescreva e complemente o conteúdo. A conversão não deve publicar
automaticamente o artigo.

### RF-009 --- Revisão e publicação

O artigo criado deverá seguir o fluxo editorial já existente no blog,
incluindo revisão e publicação. A Central de Notícias não deve contornar
as validações e permissões do CMS.

### RF-010 --- Histórico de recolhas

O sistema deve guardar o histórico das execuções, incluindo fonte ou
conjunto de fontes processadas, início e fim, estado, quantidade de
itens encontrados e novos registos criados, e erros relevantes.

### RF-011 --- Tratamento de erros

O sistema deve registar falhas de acesso, respostas inválidas, timeouts
e erros de parsing. Sempre que possível, uma falha numa fonte não deverá
interromper a recolha das restantes.

## 7. Fluxo editorial

Estados sugeridos para a notícia recolhida: 1. **Por analisar** ---
recolhida, ainda sem decisão editorial. 2. **Seleccionada** ---
considerada relevante para possível desenvolvimento. 3. **Rejeitada**
--- não será trabalhada no fluxo editorial actual. 4. **Em redacção**
--- associada a um artigo em preparação. 5. **Concluída** --- artigo
associado já foi publicado ou o fluxo foi encerrado.

O estado do artigo deve continuar a ser gerido pelo CMS. O estado da
notícia recolhida e o estado do artigo são conceitos distintos.

### 7.1 Fluxo principal

1.  Uma fonte activa é consultada manualmente ou pelo agendador.
2.  Os itens são normalizados e comparados com os registos existentes.
3.  Os novos itens são guardados com estado **Por analisar**.
4.  O editor consulta a Central de Notícias e analisa os itens.
5.  O editor selecciona ou rejeita o item e pode adicionar notas.
6.  Um item seleccionado pode ser convertido num rascunho.
7.  O redactor prepara o artigo no CMS.
8.  O artigo segue o processo normal de revisão e publicação.
9.  A relação entre o artigo e a notícia original permanece consultável.

## 8. Interface e experiência de utilização

### 8.1 Navegação

Adicionar uma entrada **Central de Notícias** à área administrativa,
visível apenas para utilizadores autorizados.

### 8.2 Ecrã principal

O ecrã principal deverá incluir listagem de notícias, pesquisa, filtros,
indicação do estado editorial, acção para iniciar recolha manual (quando
autorizada) e acesso ao histórico de recolhas.

### 8.3 Ecrã de detalhe

Deverá apresentar os dados disponíveis da notícia e as acções
permitidas: abrir a fonte original, seleccionar, rejeitar, adicionar
nota e criar rascunho quando aplicável.

### 8.4 Usabilidade

- Apresentar estados vazios e mensagens de erro compreensíveis.
- Dar feedback sobre o início e o resultado das recolhas.
- Evitar acções duplicadas durante uma operação em curso.
- Manter a interface consistente com o painel administrativo
  existente.
- Garantir utilização adequada em ecrãs de diferentes dimensões, de
  acordo com os padrões da aplicação.

## 9. Arquitectura técnica proposta

A implementação deverá integrar-se na aplicação Laravel existente,
respeitando a versão instalada, a estrutura actual do CMS e as
convenções do projecto.

### 9.1 Componentes sugeridos

- **Models:** `NewsSource`, `CollectedNews`, `NewsFetchRun`,
  `NewsEditorialNote`.
- **Migrations:** criação das tabelas e índices necessários.
- **Controllers:** endpoints administrativos para fontes, notícias e
  execuções.
- **Form Requests:** validação dos dados recebidos.
- **Policies/Gates:** autorização das acções.
- **Services:** lógica de recolha, normalização e deduplicação.
- **Jobs:** execução de recolhas fora do ciclo de pedido, quando a
  infraestrutura o permitir.
- **Scheduler:** agendamento das recolhas.
- **Events/Listeners:** opcionais, para desacoplar efeitos
  posteriores.

Os nomes são uma proposta e devem ser ajustados às convenções existentes
no repositório.

### 9.2 Recolha e filas

A recolha deverá ser executada de forma assíncrona quando a
infraestrutura o permitir, para evitar pedidos HTTP demorados. Em
alojamento partilhado, deverá ser confirmada a disponibilidade de
tarefas cron e a possibilidade de executar workers de fila. Se não
houver um worker persistente, poderá ser necessário adoptar uma
estratégia compatível com cron e com os limites do alojamento, ou
executar recolhas de forma controlada.

### 9.3 Fontes iniciais

- **RSS:** leitura de feeds RSS/Atom suportados.
- **Google News RSS:** utilização como mecanismo de descoberta.

O Google News deve ser tratado como ferramenta de descoberta. A fonte
editorial de referência deverá ser o artigo original, quando acessível e
identificável.

### 9.4 Integração com o CMS

Antes da implementação, deve ser inspeccionado o modelo de artigos
existente para identificar campos obrigatórios, estados de publicação,
relações e categorias, mecanismo de criação de rascunhos e
permissões/fluxo de revisão. A criação de rascunhos deverá reutilizar a
lógica existente, evitando duplicar regras do CMS.

## 10. Modelo de dados proposto

O modelo abaixo é conceptual. Os campos exactos devem ser ajustados à
aplicação existente.

### 10.1 `news_sources`

Armazena as fontes de recolha. Campos sugeridos: - `id`; - `name`; -
`url`; - `type` (por exemplo, `rss` ou `google_news_rss`); -
`is_active`; - `last_fetched_at` (nullable); - `created_at`; -
`updated_at`.

Poderão ser acrescentados campos de configuração específicos, desde que
validados e protegidos.

### 10.2 `collected_news`

Armazena os itens recolhidos. Campos sugeridos: - `id`; -
`news_source_id` (nullable, se a arquitectura permitir múltiplas
origens); - `title`; - `url`; - `normalized_url`; - `external_id`
(nullable); - `description` ou `excerpt` (nullable); - `published_at`
(nullable); - `fetched_at`; - `editorial_status`; - `created_at`; -
`updated_at`.

Deverão existir índices adequados para pesquisa e deduplicação. A
escolha entre guardar uma fonte principal ou suportar várias fontes para
o mesmo item deverá ser definida durante a implementação.

### 10.3 `news_fetch_runs`

Regista cada execução de recolha. Campos sugeridos: - `id`; -
`news_source_id` (nullable, para execuções agregadas); - `status`; -
`started_at`; - `finished_at` (nullable); - `items_found`; -
`items_created`; - `error_message` (nullable); - `created_at`; -
`updated_at`.

### 10.4 `news_editorial_notes`

Guarda notas editoriais associadas a uma notícia. Campos sugeridos: -
`id`; - `collected_news_id`; - `user_id`; - `note`; - `created_at`; -
`updated_at`.

### 10.5 `article_news_sources`

Relação sugerida entre artigos existentes e notícias recolhidas, caso a
estrutura do CMS não tenha já um mecanismo equivalente. Campos
sugeridos: - `id`; - `article_id`; - `collected_news_id`; -
`created_at`; - `updated_at`.

Deve ser aplicada uma restrição de unicidade adequada para evitar
associações duplicadas.

## 11. Regras de negócio

1.  Apenas fontes activas participam nas recolhas agendadas.
2.  Uma notícia recém-recolhida começa no estado **Por analisar**.
3.  A recolha repetida do mesmo item não deve criar duplicados.
4.  Uma notícia rejeitada não pode ser convertida em artigo sem uma
    acção editorial explícita.
5.  A conversão cria um rascunho, nunca uma publicação automática.
6.  A origem da notícia deve permanecer associada ao artigo.
7.  Erros de recolha devem ser registados para diagnóstico.
8.  Os estados editoriais devem ser validados no servidor.
9.  A desactivação de uma fonte não deve apagar notícias anteriormente
    recolhidas.
10. A remoção de registos deve respeitar as relações e a política de
    retenção definida para a aplicação.

## 12. Requisitos não funcionais

### 12.1 Desempenho

- A listagem deve utilizar paginação.
- A recolha não deve bloquear desnecessariamente os pedidos do painel.
- Devem existir índices para os campos usados frequentemente em
  filtros e deduplicação.

### 12.2 Segurança

- Todas as rotas administrativas devem exigir autenticação e
  autorização.
- Os dados recebidos das fontes devem ser tratados como conteúdo
  externo não confiável.
- O sistema não deve executar HTML ou scripts provenientes dos feeds.
- URLs e redireccionamentos devem ser tratados com cuidado para
  reduzir riscos de SSRF e acesso a recursos internos.
- Erros técnicos detalhados não devem ser expostos a utilizadores sem
  permissão.

### 12.3 Fiabilidade

- Uma falha numa fonte deve ser isolada sempre que possível.
- As execuções devem ter estados claros e registos de erro.
- A lógica de recolha deve ser idempotente, para que uma repetição não
  crie duplicados.

### 12.4 Manutenção

- Separar parsing, normalização, persistência e lógica editorial.
- Manter testes para os principais cenários.
- Evitar dependência desnecessária de fornecedores externos no núcleo
  do módulo.

### 12.5 Direitos de autor e responsabilidade editorial

A Central de Notícias deve funcionar como ferramenta de descoberta e
organização, não como mecanismo de republicação automática. O sistema
deve preservar links e metadados, limitar o armazenamento de conteúdo de
terceiros ao necessário e exigir redacção editorial própria antes da
publicação. A equipa deverá respeitar os termos das fontes e os direitos
aplicáveis.

## 13. Estados e resultados das recolhas

Estados sugeridos para uma execução: - `pending` --- criada, ainda não
iniciada; - `running` --- em execução; - `completed` --- concluída; -
`completed_with_errors` --- concluída com falhas em uma ou mais
fontes; - `failed` --- falha que impediu a conclusão.

A interface deve distinguir uma execução concluída sem novos itens de
uma execução falhada.

## 14. Testes e critérios de aceitação

### 14.1 Testes

Devem ser previstos testes para: - criação, edição, activação e
desactivação de fontes; - recolha de feed válido; - tratamento de feed
inválido ou indisponível; - normalização de URLs; - deduplicação de
itens; - filtros e paginação; - transições de estado editorial; -
permissões por papel; - criação de rascunho e associação à notícia
original; - registo do histórico de recolhas.

### 14.2 Critérios de aceitação do MVP

O MVP será considerado funcional quando: 1. Um utilizador autorizado
conseguir criar e gerir fontes. 2. O sistema conseguir recolher itens de
uma fonte RSS suportada. 3. Uma recolha repetida não criar duplicados
para o mesmo item. 4. As notícias aparecerem numa lista com pesquisa e
filtros essenciais. 5. O editor conseguir seleccionar ou rejeitar uma
notícia e registar notas. 6. Uma notícia seleccionada puder originar um
rascunho no CMS. 7. O rascunho mantiver a referência à notícia original. 8. Nenhuma recolha ou conversão publicar automaticamente um artigo. 9. O
histórico permitir consultar o resultado e os erros das execuções. 10.
As acções estiverem protegidas por autenticação e autorização.

## 15. Plano de implementação por fases

### Fase 1 --- Inspecção e preparação

- Confirmar a versão do Laravel e a estrutura do projecto.
- Inspeccionar o CMS e o sistema de permissões.
- Definir a estratégia de filas e cron compatível com o alojamento.
- Fechar o modelo de dados e os estados.

### Fase 2 --- Núcleo de fontes e recolha

- Criar migrations e models.
- Implementar gestão de fontes.
- Implementar parser RSS e normalização.
- Implementar deduplicação.
- Registar execuções e erros.

### Fase 3 --- Central editorial

- Criar listagem, pesquisa e filtros.
- Criar página de detalhe.
- Implementar selecção, rejeição e notas.
- Aplicar policies e validações.

### Fase 4 --- Integração com artigos

- Integrar com o modelo de artigos existente.
- Criar rascunhos a partir de notícias seleccionadas.
- Preservar a relação com a fonte original.
- Validar o fluxo de revisão e publicação.

### Fase 5 --- Agendamento, testes e estabilização

- Configurar recolhas agendadas.
- Testar os cenários de falha e repetição.
- Validar o comportamento no ambiente de alojamento.
- Rever logs, permissões e experiência de utilização.

## 16. Métricas de sucesso

As métricas iniciais deverão ajudar a avaliar a utilidade operacional do
módulo: - número de notícias recolhidas por período; - proporção de
itens duplicados detectados; - número e proporção de notícias
analisadas; - número de notícias seleccionadas; - número de rascunhos
criados a partir da Central; - taxa de sucesso das execuções de
recolha; - tempo médio entre recolha e decisão editorial.

As métricas devem ser usadas para compreender o fluxo, não para
substituir a avaliação editorial.

## 17. Riscos e mitigações

---

Risco Impacto possível Mitigação

---

Feed indisponível ou Interrupção da recolha Registar erros, isolar
alterado de uma fonte falhas e permitir nova
tentativa

Limites do alojamento Jobs demorados ou não Validar cron/worker e
partilhado executados desenhar a recolha
dentro dos limites
disponíveis

Duplicados Ruído na triagem Normalização e
editorial estratégia de
deduplicação testada

Conteúdo incompleto nos Menos contexto para a Manter o link para a
feeds decisão editorial fonte original

Republicação indevida Risco jurídico e Não publicar
reputacional automaticamente; exigir
redacção e revisão

Alterações no CMS Falha na criação de Reutilizar os serviços
rascunhos e regras existentes e
cobrir a integração com
testes
-----------------------------------------------------------------------

## 18. Pressupostos e questões em aberto

### Pressupostos

- A aplicação Laravel já possui um painel administrativo e um CMS de
  artigos.
- A autenticação e as permissões existentes poderão ser reutilizadas.
- O módulo será inicialmente utilizado por uma equipa editorial
  interna.
- A recolha inicial será baseada em feeds RSS e Google News RSS.

### Questões a confirmar antes da implementação

1.  Qual é a versão exacta do Laravel e do PHP em produção?
2.  Qual é a estrutura actual do modelo de artigos?
3.  Que sistema de permissões está instalado?
4.  O alojamento permite executar tarefas cron com a frequência
    necessária?
5.  É possível manter um worker de filas activo ou será necessário
    executar jobs por cron?
6.  Que fontes e temas devem integrar a primeira configuração?
7.  Qual será a política de retenção de notícias rejeitadas e dos logs
    de recolha?

## 19. Recomendação para o MVP

Implementar primeiro uma versão nativa no Laravel, centrada em RSS e
Google News RSS, com gestão de fontes, recolha, deduplicação, central
editorial e criação de rascunhos. Manter a decisão editorial e a
publicação sob controlo humano.

A integração com IA, classificações automáticas e análises avançadas
deverá ser considerada apenas depois de o fluxo básico estar estável e
de a equipa validar a utilidade do módulo.

---

**Fim do documento**
