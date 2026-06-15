<?php

namespace App\service\user;

use App\models\user\UserModal;
use Exception;

class UserService
{

    private $userModal;

    public function __construct()
    {
        $this->userModal = new UserModal;
    }

    public function getDashboardUser($id)
    {
        //Dados Usuario
        $modalUser = $this->userModal->findUserById($id);

        if (!isset($modalUser)) {
            return null;
        }

        return [
            'nome' => $modalUser['nome']
        ];
    }


    public function createUser($dados)
    {

        //Verifica se CPF já esta registrado
        $cpfModel = $this->userModal->checkCpfExists($dados['cpf']);
        
        if ($cpfModel === true) {
            throw new Exception("Usuario já esta registrado", 400);
        }

        //Cria hash da senha do usuario
        $hash = password_hash($dados['senha'], PASSWORD_DEFAULT);
        $dados['senha'] = $hash;

        //Definir permissão do usuario
        $dados['permissoes'] = ($dados['permissoes'] === true) ? "administrador" : "usuario" ;

        //Creação de dados de usuario
        $modalUser = $this->userModal->createUsuario($dados);

        if ($modalUser !== true) {
            throw new Exception("Usuario não pode ser cadastrado");

        }

        return true;
    }
}
