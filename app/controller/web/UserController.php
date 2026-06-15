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

    public function index()
    {
        try {
            //Passa pela verificação de COOKIES
            $user = $this->webAuthMiddleware->handle();

            //Verificar se tem permissão de admin
            if (!PermissionHelper::isAdmin($user)) {
                throw new \Exception("Usuario não tem permissão", 401);
            }

            require_once VIEW_PATH . "/createUser.php";

        } catch (\Exception $e) {
            require_once VIEW_PATH . "/dashboard.php";
        }

    }
}
