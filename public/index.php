<?php

require_once '../settings/config.php';

//Realiza tratamento de URL da página
$base = "/projetos_pessoais/sistema-de-horarios-mvc";

$uri = parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
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
$action(...$params);
