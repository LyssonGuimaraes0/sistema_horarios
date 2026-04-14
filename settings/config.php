<?php 


error_reporting(E_ALL & ~E_WARNING);

// raiz do projeto 
define('BASE_PATH', dirname(__DIR__));

// pastas principais
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', BASE_PATH . '/config');

// subpastas
define('CONTROLLER_PATH', APP_PATH . '/controllers');
define('MODEL_PATH', APP_PATH . '/models');
define('VIEW_PATH', APP_PATH . '/views');
define('HELPER_PATH', BASE_PATH . '/helpers');




?>