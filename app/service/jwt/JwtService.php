<?php

namespace App\service\jwt;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use Exception;

class JwtService
{

    private $key;

    public function __construct()
    {
        $this->key = $_ENV['KEY'];
    }
    //Gera Token
    public function generate($user)
    {
        $payload = [
            'id' => $user['id'],
            'email' => $user['email'],
            'cargo' => $user['cargo'],
            'role' => $user['permissoes'],
            'exp' => time() + 60 * 15
        ];

        return JWT::encode(
            $payload,
            $this->key,
            'HS256'
        );
    }


    //Valida Token
    public function validate($token)
    {
        try {

            return JWT::decode(
                $token,
                new Key($this->key, 'HS256')
            );
            
        } catch (Exception $e) {
            return null;
        }
    }
}