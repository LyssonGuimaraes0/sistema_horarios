<?php

require_once '../vendor/autoload.php';
require_once '../router/router.php';

// Carrega o .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

require_once 'cors.php';

/* error_reporting(E_ALL & ~E_WARNING); */

// raiz do projeto 
define('BASE_PATH', dirname(__DIR__));

// pastas principais
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', BASE_PATH . '/config');

// subpastas
define('CONTROLLER_PATH', APP_PATH . '/controllers');
define('MODEL_PATH', APP_PATH . '/models');
define('VIEW_PATH', APP_PATH . '/view');
define('COMPONENTS_PATH', VIEW_PATH . '/components');
define('HELPER_PATH', APP_PATH . '/helpers');

//Caminho URL
define('BASE_URL', '/projetos_pessoais/sistema-de-horarios-mvc');
define('SCRIPT_URL', BASE_URL . "/public" .  '/assets/js');


