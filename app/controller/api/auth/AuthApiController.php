<?php

namespace App\controller\api\auth;

use App\controller\api\ApiController;
use App\service\user\AuthUserService;

class AuthApiController extends ApiController
{

    private $authUserService;

    public function __construct()
    {
        $this->authUserService = new AuthUserService();
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

}






?>