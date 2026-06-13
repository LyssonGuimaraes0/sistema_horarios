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
}


?>