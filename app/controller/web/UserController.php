<?php

namespace App\controller\web;

use App\middleware\WebAuthMiddleware;
use App\helpers\PermissionHelper;


class UserController
{

    private $webAuthMiddleware;

    public function __construct()
    {
        $this->webAuthMiddleware = new WebAuthMiddleware;
    }

    public function create()
    {
        //Passa pela verificação de COOKIES
        $user = $this->webAuthMiddleware->handle();


        //Verifica se usuario tem permissão de admin
        if (!PermissionHelper::isAdmin($user)) {
            header('Location:' . BASE_URL . "/user/dashboard");
        }

        require_once VIEW_PATH . "/createUser.php";

    }

    public function management()
    {
        try {
            //Passa pela verificação de COOKIES
            $user = $this->webAuthMiddleware->handle();

            //Verificar se tem permissão de admin
            if (!PermissionHelper::isAdmin($user)) {
                throw new \Exception("Usuario não tem permissão", 401);
            }

            require_once VIEW_PATH . "/manageUser.php";

        } catch (\Exception $e) {
            require_once VIEW_PATH . "/dashboard.php";
        }

    }
}
