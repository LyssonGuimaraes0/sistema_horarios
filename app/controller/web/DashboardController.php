<?php

namespace App\controller\web;

use App\middleware\WebAuthMiddleware;
use App\helpers\PermissionHelper;

class DashboardController
{

    private $webAuthMiddleware;

    public function __construct()
    {
        $this->webAuthMiddleware = new WebAuthMiddleware;
    }

    public function index()
    {
        //Passa pela verificação de COOKIES
        $user = $this->webAuthMiddleware->handle();
        
        require_once VIEW_PATH . "/dashboard.php";
    }
}


?>