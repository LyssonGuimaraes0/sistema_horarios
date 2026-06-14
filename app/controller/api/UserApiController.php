<?php

namespace App\controller\api;

use App\service\user\AuthUserService;
use App\service\user\UserService;
use App\controller\api\ApiController;
use App\middleware\AuthMiddleware;
use Exception;

class UserApiController extends ApiController
{

    private $authUserService;
    private $userService;
    private $authMiddleware;

    public function __construct()
    {
        $this->authUserService = new AuthUserService;
        $this->userService = new UserService;
        $this->authMiddleware = new AuthMiddleware;
    }

    //Buscar Dados de Usuario
    public function show()
    {
        try {

            //Coleta id do usuario Logado
            $user = $this->authMiddleware->handle();

            $response = $this->userService->getDashboardUser($user->id);

            if ($response == null) {
                throw new Exception();
            }

            return $this->success($response);
        } catch (Exception) {
            return $this->error('Dados não encontrado', 404);
        }
    }

    public function create()
    {
        try {

            //Coleta id do usuario Logado
            /* $user = $this->authMiddleware->handle(); */

            $dados = json_decode(file_get_contents('php://input'),true);

            $this->userService->createUser($dados);

            

            var_dump($dados);
        } catch (Exception) {
            return $this->error('Usuario não pode ser cadastrado', 404);
        }
    }
}
