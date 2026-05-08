<?php

namespace App\controller\api\auth;

use App\controller\api\ApiController;
use App\service\user\AuthUserService;

class AuthApiController extends ApiController
{
    public function login()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $username = $data['username'];
        $password = $data['password'];

        $AuthUserService = new AuthUserService;

        $consultDate = $AuthUserService->AuthUser($username,$password);

        var_dump($consultDate);
        exit;

/*         $dados = [
            'user' => $data['username'],
            'password' => $data['password']
        ]; */




    }
}






?>