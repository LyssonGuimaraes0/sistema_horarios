# Sistema de Frequencias

## Visão geral

`Sistema de Frequencias` é um sistema PHP MVC para registro e gestão de ponto, controle de feriados e administração de usuários.

O projeto possui:

- interface web para login, dashboard, registro de ponto, cadastro de usuário e gerenciamento de feriados
- API REST para operações de frequência, usuário, holiday e autenticação
- geração de PDF de folha de ponto
- controle de permissões via JWT e middleware

## Tecnologias utilizadas

- Linguagem: **PHP**
- Autoload: **PSR-4** via `composer.json`
- Dependências:
  - `vlucas/phpdotenv`
  - `firebase/php-jwt`
  - `dompdf/dompdf`

## Estrutura do projeto

- `app/controller/web/` - controladores para páginas web
- `app/controller/api/` - controladores de API JSON
- `app/service/` - serviços de negócio e processamento
- `app/models/` - modelos de dados
- `app/view/` - views/templates PHP
- `router/router.php` - definição e correspondência de rotas
- `public/index.php` - ponto de entrada principal
- `settings/config.php` - configurações globais e autoload
- `storage/uploads/` - arquivos enviados pelo usuário

## Metodologia de arquitetura

O projeto adota o padrão **MVC**:

- Model: regras de negócio e dados em `app/models` e `app/service`
- View: templates reais em `app/view`
- Controller: roteamento e ações em `app/controller`

O roteador em `router/router.php` faz correspondência entre URI e controlador, incluindo parâmetros dinâmicos no formato `{id}`, `{year}`, `{month}`.

## Autenticação e autorização

- `App\service\jwt\JwtService` gera e valida tokens JWT
- `App\middleware\AuthMiddleware` protege rotas da API
- `App\middleware\WebAuthMiddleware` protege páginas web
- `App\helpers\PermissionHelper` valida permissões de administrador

## Rotas disponíveis

### Rotas web (renderização de views)

- `GET /` → `App\controller\web\LoginController@index`
- `GET /user/dashboard` → `App\controller\web\DashboardController@index`
- `GET /forgotpassword` → `App\controller\web\ForgotPasswordController@index`
- `GET /user/attendance` → `App\controller\web\AttendanceController@index`
- `GET /user/create` → `App\controller\web\UserController@create`
- `GET /user/management` → `App\controller\web\UserController@management`
- `GET /admin/attendance/holiday` → `App\controller\web\HolidayController@index`

### Rotas API

#### GET

- `GET /api/user` → `App\controller\api\UserApiController@show`
- `GET /api/user/sectors` → `App\controller\api\UserApiController@getSectors`
- `GET /api/user/sector/users` → `App\controller\api\UserApiController@getUsersBySector`
- `GET /api/user/details` → `App\controller\api\UserApiController@getUserDetails`
- `GET /api/user/{id}/timesheets` → `App\controller\api\AttendanceController@getUserTimesheet`
- `GET /api/attendance/available-periods` → `App\controller\api\AttendanceController@availablePeriods`
- `GET /api/attendance/calendar/{year}/{month}` → `App\controller\api\AttendanceController@getCalendar`
- `GET /api/attendance/report` → `App\controller\api\AttendanceController@attendancePdf`
- `GET /api/holiday` → `App\controller\api\HolidayController@getHolidays`

#### POST

- `POST /api/auth/login` → `App\controller\api\auth\AuthApiController@login`
- `POST /api/auth/logout` → `App\controller\api\auth\AuthApiController@logout`
- `POST /api/user/attendance/create` → `App\controller\api\AttendanceController@create`
- `POST /api/user/create` → `App\controller\api\UserApiController@create`
- `POST /api/user/createAttachment` → `App\controller\api\AttendanceController@createAttachment`
- `POST /api/attendance/monthly` → `App\controller\api\AttendanceController@storeMonthlyAttendance`
- `POST /api/holiday/create` → `App\controller\api\HolidayController@create`
- `POST /api/optional-holidays/create` → `App\controller\api\HolidayController@store`

#### PATCH

- `PATCH /api/auth/forgotpassword` → `App\controller\api\auth\AuthApiController@login` (reutiliza ação de login)
- `PATCH /api/user/attendance` → `App\controller\api\AttendanceController@updateAttendance`

#### DELETE

- `DELETE /api/user/attendance/delete` → `App\controller\api\AttendanceController@deleteAttendance`
- `DELETE /api/user/deleteAttachment` → `App\controller\api\AttendanceController@deleteAttachment`
- `DELETE /api/holiday/delete` → `App\controller\api\HolidayController@delete`

## Principais funcionalidades

- login/logout com JWT
- dashboard de frequência do usuário
- registro, edição, exclusão e consulta de ponto
- upload de atestado e folha mensal
- geração de PDF de folha de ponto
- gerenciamento de usuários e setores (admin)
- cadastro e exclusão de feriados e pontos facultativos

## Configuração necessária

- Defina variáveis em `.env` como `RAIZ_URL` e `KEY`
- O arquivo `settings/config.php` carrega `vendor/autoload.php` e inicializa o ambiente
- `public/index.php` é o ponto de entrada para todas as requisições

## Fluxo de execução detalhado

### 1. Rota definida em `router/router.php`

- O arquivo `router/router.php` mapeia cada URL para uma ação de controlador.
- Exemplo:
  - `GET /user/dashboard` chama `web('DashboardController', 'index')`
  - `POST /api/user/attendance/create` chama `api('AttendanceController', 'create')`
- O roteador também analisa parâmetros dinâmicos como `{id}`, `{year}` e `{month}`.

### 2. Router carrega o controller

- A função `loadRouter()` cria a classe de controller com namespace `App\controller\web` ou `App\controller\api`.
- O método do controller é chamado com os parâmetros extraídos da URL.

### 3. Controller processa a requisição

- Controllers web carregam uma view usando `require_once VIEW_PATH . '/arquivo.php'`.
- Controllers de API usam `ApiController` para retornar JSON com `success()` ou `error()`.
- Antes de executar a lógica, o controller valida autenticação/autorização via middleware (`AuthMiddleware` ou `WebAuthMiddleware`).

### 4. Service executa a lógica de negócio

- O controller delega a maior parte do processamento para classes em `app/service/`.
- Exemplo:
  - `AttendanceController@create` chama `AttendanceService->createAttendance()`
  - `UserApiController@show` chama `UserService->getDashboardUser()`
- O service pode consultar modelos, validar dados e aplicar regras de negócio.

### 5. Model / banco de dados (quando necessário)

- Os services podem usar classes de modelo ou consultas diretas para acessar o banco.
- Exemplo de uso típico:
  - buscar usuário por ID
  - salvar registro de ponto
  - buscar feriados ou folhas mensais
- Mesmo que o projeto não tenha um `Model` único para cada tabela, a camada de serviço funciona como intermediária entre controller e dados.

### 6. Retorno para o controller

- O service devolve dados ou lança exceções quando ocorre erro.
- O controller interpreta o retorno e escolhe o formato de resposta:
  - view renderizada para rotas web
  - JSON padronizado para rotas API

### 7. Resposta final

- Para páginas web:
  - o controller inclui a view e exibe HTML ao navegador.
- Para API:
  - `ApiController::success()` retorna JSON com `success: true` e `data`.
  - `ApiController::error()` retorna JSON com `success: false` e código HTTP.

## Como editar

- Atualize rotas em `router/router.php`
- Crie/edite controladores em `app/controller/web` e `app/controller/api`
- Adicione regras de negócio em `app/service`
- Edite views em `app/view`
- Configure permissões em `app/helpers/PermissionHelper.php`

---

