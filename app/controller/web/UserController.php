<?php

namespace App\controller\web;

use App\middleware\WebAuthMiddleware;


class UserController
{

    private $webAuthMiddleware;

    public function __construct()
    {
        $this->webAuthMiddleware = new WebAuthMiddleware;
    }

    public function index()
    {

        $user = $this->webAuthMiddleware->handle();

        require_once VIEW_PATH . "/createUser.php";
    }
}
