<?php

namespace App\controller\web;

use App\middleware\AuthMiddleware;
use App\helpers\PermissionHelper;

class DashboardController
{

    private $authMiddleware;

    public function __construct()
    {
        $this->authMiddleware = new AuthMiddleware;
    }

    public function index()
    {
        //Passa pela verificação de COOKIES
        $user = $this->authMiddleware->handle();
        
        require_once VIEW_PATH . "/dashboard.php";
    }
}


?>