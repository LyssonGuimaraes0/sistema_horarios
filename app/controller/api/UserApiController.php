<?php

namespace App\controller\api;

use App\service\user\AuthUserService;
use App\service\user\UserService;
use App\controller\api\ApiController;
use App\middleware\AuthMiddleware;
use App\helpers\PermissionHelper;
use Exception;

class UserApiController extends ApiController
{

    private $authUserService;
    private $userService;
    private $authMiddleware;
    private $permissionHelper;

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
            $user = $this->authMiddleware->handle();

            //Verificar se tem permissão de admin
            if (!PermissionHelper::isAdmin($user)) {
                throw new Exception("Usuario não tem permissão", 401);
            }

            $dados = json_decode(file_get_contents('php://input'), true);

            $this->userService->createUser($dados);

            return $this->success('Usuário cadastrado com sucesso', 201);

        } catch (Exception $e) {
            // Pegamos o código original da Exception
            $statusCode = $e->getCode();

            if ($statusCode === 401) {
                return $this->error($e->getMessage(), 401);
            }

            return $this->error($e->getMessage(), 200);
        }
    }
}
