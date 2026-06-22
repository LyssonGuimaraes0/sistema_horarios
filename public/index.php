<?php

require_once '../settings/config.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');


//Realiza tratamento de URL da página
$base = $_ENV['RAIZ_URL'];

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request = $_SERVER['REQUEST_METHOD'];

$uri = str_replace($base, '', $uri);
//=========================================

$match = matchRoute($uri, $router[$request]);

if (!$match) {
    http_response_code(404);
    echo "Rota não encontrada";
    exit;
}

[$action, $params] = $match;



// 👇 AQUI ACONTECE A MÁGICA
$action(...array_values($params));
