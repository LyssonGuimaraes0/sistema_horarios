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

        //Rotas Para Buscar dados de usuario
        '/api/user' => api('UserApiController', 'show'),

    ],
    'POST' => [
        //Rota de login de usuario
        '/api/auth/login' => api('auth\AuthApiController', 'login'),
        '/api/auth/logout' => api('auth\AuthApiController', 'logout')

    ],

    'PATCH' => [
        //Rota para alterar senha do Usuario
        '/api/auth/forgotpassword' => api('auth\AuthApiController', 'login')
    ]

];