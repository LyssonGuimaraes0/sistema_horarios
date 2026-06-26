<?php

namespace App\controller\web;

use App\middleware\WebAuthMiddleware;
use App\helpers\PermissionHelper;

class HolidayController
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

        //Verifica se usuario tem permissão de admin
        if (!PermissionHelper::isAdmin($user)) {
            header('Location:' . BASE_URL . "/user/dashboard");
        }
        
        require_once VIEW_PATH . "/admin/holiday.php";
    }
}


?>