<?php

namespace App\controller\api\auth;

use App\controller\api\ApiController;
use App\service\user\AuthUserService;
use App\middleware\AuthMiddleware;

class AuthApiController extends ApiController
{

    private $authUserService;
    private $authMiddleware;

    public function __construct()
    {
        $this->authUserService = new AuthUserService();
        $this->authMiddleware = new AuthMiddleware;
    }

    //Função de Login do Usuario
    public function login()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $username = preg_replace('/[^a-z0-9_-]/', '', $data['username']);
        $password = $data['password'];


        $response = $this->authUserService->login($username, $password);

        //Verifica se a resposta falhou
        if ($response['success'] == false) {
            $this->error('Email ou senha invalido!', 401);
            return;
        }

        //Retorna sucesso caso consiga logar
        return $this->success('');

    }

    public function logout()
    {
        $response = $this->authUserService->logout();

        if ($response['success'] != true) {
            return $this->error('', 401);
        }
        $this->authMiddleware->handle();
        return $this->success('');
    }

}


?>