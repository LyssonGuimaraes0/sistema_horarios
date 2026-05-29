<?php

namespace App\service\user;

use App\models\user\AuthUserModal;
use App\service\jwt\JwtService;
use Exception;

class AuthUserService
{

    private $authUserModal;
    private $jwtService;

    public function __construct()
    {
        $this->authUserModal = new AuthUserModal;
        $this->jwtService = new JwtService;
    }

    public function login($username, $password)
    {
        try {
            $consultData = $this->authUserModal->FindUserByUsername($username);

            if (!$consultData) {
                throw new Exception();
            }

            $password_hash = $consultData['senha'];

            if (!password_verify($password, $password_hash)) {
                throw new Exception();
            }

            //Gera Token com JWT
            $acessToken = $this->jwtService->generate($consultData);

            //Gera CSRF
            $csrfToken = bin2hex(random_bytes(32));

            //Armazena JWT Token em COOKIES

            setcookie(
                'access_token',
                $acessToken,
                [
                    'httponly' => true,
                    'path' => '/',
                    'samesite' => 'Lax'
                ]
            );

            //Armazena CSRF em COOKIES 

            setcookie(
                'csrf_token',
                $csrfToken,
                [
                    'httponly' => false,
                    'path' => '/',
                    'samesite' => 'Lax'
                ]
            );

        } catch (Exception) {
            return [
                'success' => false
            ];
        }

        return [
            'success' => true
        ];
    }

    //Ação para deslogar usuario

        public function logout()
    {
        // Remove Access Token
        setcookie(
            'access_token',
            '',
            [
                'expires' => time() - 3600,
                'httponly' => true,
                'path' => '/',
                'samesite' => 'Lax'
            ]
        );

        // Remove CSRF Token
        setcookie(
            'csrf_token',
            '',
            [
                'expires' => time() - 3600,
                'httponly' => false,
                'path' => '/',
                'samesite' => 'Lax'
            ]
        );

        return [
            'success' => true,
        ];
    }

    public function getUserByEmail(string $email)
    {
        $consultData = $this->authUserModal->FindUserByEmail($email);
    }


}





?>