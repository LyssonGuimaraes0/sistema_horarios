<?php

namespace App\middleware;

use App\service\jwt\JwtService;

class AuthMiddleware extends JwtService
{

    public function handle()
    {
        if (!isset($_COOKIE['access_token'])) {
            header(
                'Location:'. BASE_URL
            );

            exit;
        }

        $user = $this->validate(
            $_COOKIE['access_token']
        );

        if (!$user) {
            header(
                'Location:' . BASE_URL
            );

            exit;
        }

        return $user;
    }


}