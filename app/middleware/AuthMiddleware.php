<?php

namespace App\middleware;

use App\controller\api\ApiController;
use App\service\jwt\JwtService;

class AuthMiddleware extends ApiController
{

private $jwtService;

    public function __construct(){
        $this->jwtService = New JwtService;
    }

    public function handle()
    {
        if (!isset($_COOKIE['access_token'])) {
            
            
            exit;
        }

        $user = $this->jwtService->validate(
            $_COOKIE['access_token']
        );

        if (!$user) {
            header(
                'Location:' . BASE_URL
            );
            $this->error("Token não encontrado");
            exit;
        }

        return $user;
    }


}