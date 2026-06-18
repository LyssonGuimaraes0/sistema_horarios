<?php

function loadRouter(string $type, string $controller, string $action, array $params = [])
{
    try {
        $controllerNameSpace = "App\\controller\\{$type}\\{$controller}";

        if (!class_exists($controllerNameSpace)) {
            throw new Exception("Error Processing Request");
        }


        $controllerInstance = new $controllerNameSpace();

        if (!method_exists($controllerInstance, $action)) {
            throw new Exception("Error Processing Request");
        }


        $controllerInstance->$action(...$params);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

//Função para verificar Parametos
function matchRoute($uri, $routes)
{

    foreach ($routes as $route => $action) {

        $paramNames = [];
        preg_match_all('/\{([^}]+)\}/', $route, $matches);
        $paramNames = $matches[1];

        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $route);
        $pattern = "#^$pattern$#";


        if (preg_match($pattern, $uri, $matches)) {

            array_shift($matches); // remove match completo

            $paramsAssoc = [];

            foreach ($paramNames as $index => $name) {
                $paramsAssoc[$name] = $matches[$index] ?? null;
            }

            return [$action, $paramsAssoc];
        }
    }


    return null;
}


function web($controller, $action)
{
    return fn(...$params) => loadRouter('web', $controller, $action, $params);
}

function api($controller, $action)
{
    return fn(...$params) => loadRouter('api', $controller, $action, $params);
}

$router = [
    'GET' => [
        '/' => web('LoginController', 'index'),
        '/user/dashboard' => web('DashboardController', 'index'),
        '/forgotpassword' => web('ForgotPasswordController', 'index'),

        //Rota para página de registra horario
        '/user/attendance' => web('AttendanceController', 'index'),

        //Rota para página de criação de usuario
        '/user/create' => web('UserController', 'create'),

        //Rota para página de gerenciamento de usuarios
        '/user/management' => web('UserController', 'management'),

        //Rotas Para Buscar dados de usuario
        '/api/user' => api('UserApiController', 'show'),

        //Rota de retorno de setores
        '/api/user/sectors' => api('UserApiController', 'getSectors'),

        //Rota de retorno de Usuarios Por setor
        '/api/user/sector/users' => api('UserApiController', 'getUsersBySector'),

        //Rota de retorno de detalhes de usuario
        '/api/user/details' => api('UserApiController', 'getUserDetails'),

        //Rota de Folhas mensal de usuario por ano
        '/api/user/{id}/timesheets' => api('AttendanceController', 'getUserTimesheet'),

        //Rota para buscar Meses Validos
        '/api/attendance/available-periods' => api('AttendanceController', 'availablePeriods'),

        //Rota busca de horarios registrados
        '/api/attendance/calendar/{year}/{month}' => api('AttendanceController', 'getCalendar'),


    ],
    'POST' => [
        //Rota de login de usuario
        '/api/auth/login' => api('auth\AuthApiController', 'login'),
        '/api/auth/logout' => api('auth\AuthApiController', 'logout'),

        //Rota De registro de horarios
        '/api/user/attendance/create' => api('AttendanceController', 'create'),

        //Rota De criação de usuario
        '/api/user/create' => api('UserApiController', 'create'),

    ],

    'PATCH' => [
        //Rota para alterar senha do Usuario
        '/api/auth/forgotpassword' => api('auth\AuthApiController', 'login')
    ],

    'DELETE' => [
        //Rota para deleta registro de horarios
        '/api/user/attendance/delete' => api('AttendanceController', 'delete'),
    ]

];
